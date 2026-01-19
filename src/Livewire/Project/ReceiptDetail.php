<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\SynAuth\Facades\SynAuth;

class ReceiptDetail extends Component
{
    use WithFileUploads;

    public ?ProjectInvoice $invoice = null;

    public ?int $invoiceId = null;

    // Payment status
    public bool $isPaid = false;

    public ?string $paidAt = null;

    public $paymentProof;

    protected $listeners = [
        'load-receipt-detail' => 'loadInvoice',
    ];

    public function loadInvoice(int $id): void
    {
        try {
            $this->invoice = ProjectInvoice::with([
                'project',
                'project.client',
                'project.projectCategory',
                'project.division',
                'issuer',
                'signature',
                'items',
                'client',
                'receiptDocument',
            ])->findOrFail($id);
            $this->invoiceId = $id;

            // Load payment status
            $this->isPaid = (bool) $this->invoice->is_paid;
            $this->paidAt = $this->invoice->paid_at?->format('Y-m-d H:i');
            $this->paymentProof = null;
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => __('pm::invoice.not_found'),
            ]);
            $this->dispatch('close-modal-receipt-detail');
        }
    }

    #[On('close-modal-receipt-detail')]
    public function resetDetail(): void
    {
        $this->invoice = null;
        $this->invoiceId = null;
        $this->isPaid = false;
        $this->paidAt = null;
        $this->paymentProof = null;
    }

    #[Computed]
    public function receiptNumber(): string
    {
        if (! $this->invoice) {
            return '-';
        }

        // If receipt already generated, return existing number
        if ($this->invoice->receipt_number) {
            return $this->invoice->receipt_number;
        }

        // Generate auto number from Receipt document service
        return Documents::RECEIPT->handler()->generateDocumentNumber(
            $this->invoice->project,
            Documents::RECEIPT
        );
    }

    #[Computed]
    public function amountText(): string
    {
        if (! $this->invoice) {
            return '-';
        }

        // If receipt already generated, return stored text
        if ($this->invoice->receipt_amount_text) {
            return $this->invoice->receipt_amount_text;
        }

        // Generate text from total_amount
        return Terbilang::rupiah((float) $this->invoice->total_amount);
    }

    #[Computed]
    public function items()
    {
        if (! $this->invoice) {
            return collect();
        }

        return $this->invoice->items;
    }

    #[Computed]
    public function hasReceipt(): bool
    {
        return $this->invoice && $this->invoice->receipt_document_id !== null;
    }

    public function generateReceipt(): void
    {
        try {
            DB::transaction(function () {
                $this->generatePdf();
            });

            SynAuth::logActivity(
                Auth::id(),
                'pm.project.'.$this->invoice->project_id.'.receipt.generated',
                'User '.Auth::user()->name.' generated receipt file for project '.$this->invoice->project->name,
                'project.'.$this->invoice->project_id.'.receipt',
                'pm'
            );

            $this->dispatch('activity-refresh');
            $this->dispatch('invoice-refresh');
            $this->dispatch('document-uploaded');

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::receipt.pdf_generated'),
            ]);
            $this->refreshInvoice();
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function generatePdf(): void
    {
        $this->invoice->load(['items', 'issuer', 'signature', 'project', 'client']);

        // Generate receipt number if not exists
        $receiptNumber = $this->invoice->receipt_number;
        if (! $receiptNumber) {
            $receiptNumber = Documents::RECEIPT->handler()->generateDocumentNumber(
                $this->invoice->project,
                Documents::RECEIPT
            );
        }

        // Generate amount text
        $amountText = Terbilang::rupiah((float) $this->invoice->total_amount);

        // Update invoice with receipt data
        $this->invoice->update([
            'receipt_number' => $receiptNumber,
            'receipt_amount_text' => $amountText,
        ]);

        // Increment receipt index
        Documents::RECEIPT->handler()->incrementIndex($this->invoice->project, Documents::RECEIPT);

        $pdf = Pdf::loadView('pm::pdf.receipt', [
            'invoice' => $this->invoice,
            'receiptNumber' => $receiptNumber,
            'amountText' => $amountText,
        ]);

        $pdf->setPaper('a4');

        // Generate filename
        $filename = 'receipt_'.str_replace(['/', '\\', ' '], '_', $receiptNumber).'.pdf';
        $storagePath = 'projects/'.$this->invoice->project_id.'/documents/'.Documents::RECEIPT->code();

        // Delete old document if exists
        if ($this->invoice->receipt_document_id) {
            $oldDocument = ProjectDocument::find($this->invoice->receipt_document_id);
            if ($oldDocument && $oldDocument->file_path) {
                Storage::delete($oldDocument->file_path);
                $oldDocument->delete();
            }
        }

        // Store PDF
        $fullPath = $storagePath.'/'.$filename;
        Storage::put($fullPath, $pdf->output());

        // Create project document record
        $document = ProjectDocument::create([
            'user_id' => auth()->id(),
            'project_id' => $this->invoice->project_id,
            'client_id' => $this->invoice->client_id,
            'type' => Documents::RECEIPT->value,
            'number' => $receiptNumber,
            'notes' => 'Receipt '.$receiptNumber.' - Auto-generated',
            'file_path' => $fullPath,
            'file_info' => [
                'name' => $filename,
                'size' => Storage::size($fullPath),
                'mime' => 'application/pdf',
            ],
            'version' => 1,
        ]);

        // Update invoice with document reference
        $this->invoice->update([
            'receipt_document_id' => $document->id,
        ]);
    }

    public function updatedIsPaid(): void
    {
        if (! $this->isPaid) {
            // Reset payment fields when unchecked
            $this->paidAt = null;
        } else {
            // Set default paid_at to now
            $this->paidAt = now()->format('Y-m-d H:i');
        }
    }

    public function savePaymentStatus(): void
    {
        if (! $this->hasReceipt) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => __('pm::receipt.generate_first'),
            ]);

            return;
        }

        $rules = [
            'isPaid' => 'required|boolean',
        ];

        if ($this->isPaid) {
            $rules['paidAt'] = 'required|date';
            $rules['paymentProof'] = [
                'nullable',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5120),
            ];
        }

        $this->validate($rules);

        try {
            $updateData = [
                'is_paid' => $this->isPaid,
                'paid_at' => $this->isPaid && $this->paidAt ? $this->paidAt : null,
            ];

            // Handle payment proof upload
            if ($this->isPaid && $this->paymentProof) {
                $path = $this->paymentProof->store(
                    'projects/'.$this->invoice->project_id.'/payment-proofs',
                    'local'
                );

                // Delete old payment proof if exists
                if ($this->invoice->payment_proof) {
                    Storage::delete($this->invoice->payment_proof);
                }

                $updateData['payment_proof'] = $path;
            }

            $this->invoice->update($updateData);

            SynAuth::logActivity(
                Auth::id(),
                'pm.project.'.$this->invoice->project_id.'.receipt.payment_updated',
                'User '.Auth::user()->name.' updated payment status for receipt '.$this->invoice->receipt_number,
                'project.'.$this->invoice->project_id.'.receipt',
                'pm'
            );

            $this->dispatch('activity-refresh');
            $this->dispatch('invoice-refresh');

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::receipt.payment_status_updated'),
            ]);
            $this->refreshInvoice();
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function refreshInvoice(): void
    {
        if ($this->invoiceId) {
            $this->invoice = ProjectInvoice::with([
                'project',
                'project.client',
                'project.projectCategory',
                'project.division',
                'issuer',
                'signature',
                'items',
                'client',
                'receiptDocument',
            ])->find($this->invoiceId);
        }
    }

    public function render()
    {
        return view('pm::livewire.project.receipt-detail');
    }
}
