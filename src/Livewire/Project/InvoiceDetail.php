<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\ProjectInvoiceItem;

class InvoiceDetail extends Component
{
    public ?ProjectInvoice $invoice = null;

    public ?int $invoiceId = null;

    // Edit item properties
    public ?int $editItemId = null;

    public string $editItemName = '';

    public string $editItemAmount = '0';

    public bool $editItemIncludeDiscount = false;

    public int $editItemDiscountPercentage = 0;

    // New item properties
    public string $newItemName = '';

    public string $newItemAmount = '0';

    public bool $newItemIncludeDiscount = false;

    public int $newItemDiscountPercentage = 0;

    // Invoice totals
    public bool $includeVat = true;

    public int $vatPercentage = 11;

    public bool $includeDiscount = false;

    public int $discountPercentage = 0;

    public bool $includeDpp = false;

    public string $invoiceNotes = '';

    protected $listeners = [
        'load-invoice-detail' => 'loadInvoice',
    ];

    public function loadInvoice(int $id): void
    {
        try {
            $this->invoice = ProjectInvoice::with([
                'project',
                'issuer',
                'bankAccount',
                'signature',
                'items',
                'client',
            ])->findOrFail($id);
            $this->invoiceId = $id;

            // Load invoice settings
            $this->includeVat = (bool) $this->invoice->include_vat;
            $this->vatPercentage = (int) $this->invoice->vat_percentage;
            $this->includeDiscount = (bool) $this->invoice->include_discount;
            $this->discountPercentage = (int) $this->invoice->discount_percentage;
            $this->includeDpp = (bool) $this->invoice->include_dpp;
            $this->invoiceNotes = $this->invoice->notes ?? '';
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => __('pm::invoice.not_found'),
            ]);
            $this->dispatch('close-modal-invoice-detail');
        }
    }

    public function updatingNewItemAmount($value): string
    {
        return str_replace('.', '', $value);
    }

    public function updatingEditItemAmount($value): string
    {
        return str_replace('.', '', $value);
    }

    #[On('close-modal-invoice-detail')]
    public function resetDetail(): void
    {
        $this->invoice = null;
        $this->invoiceId = null;
        $this->cancelEditItem();
        $this->resetNewItem();
    }

    public function cancelEditItem(): void
    {
        $this->editItemId = null;
        $this->editItemName = '';
        $this->editItemAmount = '0';
        $this->editItemIncludeDiscount = false;
        $this->editItemDiscountPercentage = 0;
    }

    public function resetNewItem(): void
    {
        $this->newItemName = '';
        $this->newItemAmount = '0';
        $this->newItemIncludeDiscount = false;
        $this->newItemDiscountPercentage = 0;
    }

    public function editItem(int $id): void
    {
        $this->cancelEditItem();
        $item = ProjectInvoiceItem::findOrFail($id);
        $this->editItemId = $item->id;
        $this->editItemName = $item->name;
        $this->editItemAmount = (string) (int) $item->amount;
        $this->editItemIncludeDiscount = (bool) $item->include_discount;
        $this->editItemDiscountPercentage = (int) $item->discount_percentage;
    }

    public function updateItem(int $id): void
    {
        // Clean the formatted amount before validation
        $this->editItemAmount = str_replace('.', '', $this->editItemAmount);

        $this->validate([
            'editItemName' => 'required|string|max:500',
            'editItemAmount' => 'required|numeric|min:0',
            'editItemDiscountPercentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $item = ProjectInvoiceItem::findOrFail($id);
            $amount = (float) str_replace('.', '', $this->editItemAmount);
            $discountAmount = $this->editItemIncludeDiscount
                ? ($amount * $this->editItemDiscountPercentage / 100)
                : 0;

            $item->update([
                'name' => $this->editItemName,
                'amount' => $amount,
                'include_discount' => $this->editItemIncludeDiscount,
                'discount_percentage' => $this->editItemIncludeDiscount ? $this->editItemDiscountPercentage : 0,
                'discount_amount' => $discountAmount,
            ]);

            $this->cancelEditItem();
            $this->recalculateTotals();
            $this->refreshInvoice();

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::invoice.item_updated'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function storeItem(): void
    {
        // Clean the formatted amount before validation
        $this->newItemAmount = str_replace('.', '', $this->newItemAmount);

        $this->validate([
            'newItemName' => 'required|string|max:500',
            'newItemAmount' => 'required|numeric|min:0',
            'newItemDiscountPercentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $amount = (float) str_replace('.', '', $this->newItemAmount);
            $discountAmount = $this->newItemIncludeDiscount
                ? ($amount * $this->newItemDiscountPercentage / 100)
                : 0;

            ProjectInvoiceItem::create([
                'project_id' => $this->invoice->project_id,
                'project_invoice_id' => $this->invoice->id,
                'client_id' => $this->invoice->client_id,
                'name' => $this->newItemName,
                'amount' => $amount,
                'include_discount' => $this->newItemIncludeDiscount,
                'discount_percentage' => $this->newItemIncludeDiscount ? $this->newItemDiscountPercentage : 0,
                'discount_amount' => $discountAmount,
            ]);

            $this->resetNewItem();
            $this->recalculateTotals();
            $this->refreshInvoice();

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::invoice.item_added'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteItem(string $token): void
    {
        try {
            $id = ProjectInvoiceItem::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $item = ProjectInvoiceItem::findOrFail($id);
            $item->delete();

            $this->recalculateTotals();
            $this->refreshInvoice();

            $this->dispatch('synapse-confirmed');
            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::invoice.item_deleted'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updatedIncludeVat(): void
    {
        $this->recalculateTotals();
    }

    public function updatedVatPercentage(): void
    {
        $this->recalculateTotals();
    }

    public function updatedIncludeDiscount(): void
    {
        $this->recalculateTotals();
    }

    public function updatedDiscountPercentage(): void
    {
        $this->recalculateTotals();
    }

    public function updatedIncludeDpp(): void
    {
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        if (! $this->invoice) {
            return;
        }

        $items = ProjectInvoiceItem::where('project_invoice_id', $this->invoice->id)->get();

        // Calculate base amount (sum of all items after item discounts)
        $baseAmount = 0;
        foreach ($items as $item) {
            $itemTotal = $item->amount;
            if ($item->include_discount && $item->discount_percentage > 0) {
                $itemTotal -= ($item->amount * $item->discount_percentage / 100);
            }
            $baseAmount += $itemTotal;
        }

        // Calculate DPP (if include_dpp is true, DPP = 11/12 of base amount)
        $dppAmount = $this->includeDpp ? ($baseAmount * 11 / 12) : $baseAmount;

        // Calculate invoice discount
        $discountAmount = $this->includeDiscount
            ? ($dppAmount * $this->discountPercentage / 100)
            : 0;

        // Calculate sub total after discount
        $subTotal = $dppAmount - $discountAmount;

        // Calculate VAT
        $vatAmount = $this->includeVat
            ? ($subTotal * $this->vatPercentage / 100)
            : 0;

        // Calculate grand total
        $grandTotal = $subTotal + $vatAmount;

        // Update invoice
        $this->invoice->update([
            'amount' => $baseAmount,
            'include_dpp' => $this->includeDpp,
            'dpp_amount' => $this->includeDpp ? $dppAmount : 0,
            'include_discount' => $this->includeDiscount,
            'discount_percentage' => $this->includeDiscount ? $this->discountPercentage : 0,
            'discount_amount' => $discountAmount,
            'include_vat' => $this->includeVat,
            'vat_percentage' => $this->includeVat ? $this->vatPercentage : 0,
            'vat_amount' => $vatAmount,
            'total_amount' => $grandTotal,
            'notes' => $this->invoiceNotes,
        ]);
    }

    public function saveAndGeneratePdf(): void
    {
        try {
            DB::transaction(function () {
                $this->recalculateTotals();
                $this->refreshInvoice();
                $this->generatePdf();
            });

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::invoice.pdf_generated'),
            ]);
            $this->dispatch('invoice-refresh');
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
        $this->invoice->load(['items', 'issuer', 'bankAccount', 'signature', 'project', 'client']);

        $pdf = Pdf::loadView('pm::pdf.invoice', [
            'invoice' => $this->invoice,
        ]);

        $pdf->setPaper('a4');

        // Generate filename
        $filename = 'invoice_'.str_replace(['/', '\\', ' '], '_', $this->invoice->invoice_number).'.pdf';
        $storagePath = 'projects/'.$this->invoice->project_id.'/documents/'.Documents::INVOICE->code();

        // Delete old document if exists
        if ($this->invoice->invoice_document_id) {
            $oldDocument = ProjectDocument::find($this->invoice->invoice_document_id);
            if ($oldDocument && $oldDocument->file_path) {
                Storage::disk('public')->delete($oldDocument->file_path);
                $oldDocument->delete();
            }
        }

        // Store PDF
        $fullPath = $storagePath.'/'.$filename;
        Storage::disk('public')->put($fullPath, $pdf->output());

        // Create project document record
        $document = ProjectDocument::create([
            'user_id' => auth()->id(),
            'project_id' => $this->invoice->project_id,
            'client_id' => $this->invoice->client_id,
            'type' => Documents::INVOICE->value,
            'number' => $this->invoice->invoice_number,
            'notes' => 'Invoice '.$this->invoice->invoice_number.' - Auto-generated',
            'file_path' => $fullPath,
            'file_info' => [
                'name' => $filename,
                'size' => Storage::disk('public')->size($fullPath),
                'mime' => 'application/pdf',
            ],
            'version' => 1,
        ]);

        // Update invoice with document reference
        $this->invoice->update([
            'invoice_document_id' => $document->id,
        ]);
    }

    protected function refreshInvoice(): void
    {
        if ($this->invoiceId) {
            $this->invoice = ProjectInvoice::with([
                'project',
                'issuer',
                'bankAccount',
                'signature',
                'items',
                'client',
            ])->find($this->invoiceId);
        }
    }

    #[Computed]
    public function items()
    {
        if (! $this->invoice) {
            return collect();
        }

        return ProjectInvoiceItem::where('project_invoice_id', $this->invoice->id)
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function subTotal(): float
    {
        if (! $this->invoice) {
            return 0;
        }

        $total = 0;
        foreach ($this->items as $item) {
            $itemTotal = (float) $item->amount;
            if ($item->include_discount && $item->discount_percentage > 0) {
                $itemTotal -= ($item->amount * $item->discount_percentage / 100);
            }
            $total += $itemTotal;
        }

        return $total;
    }

    #[Computed]
    public function dppAmount(): float
    {
        return $this->includeDpp ? ($this->subTotal * 11 / 12) : $this->subTotal;
    }

    #[Computed]
    public function invoiceDiscountAmount(): float
    {
        return $this->includeDiscount ? ($this->dppAmount * $this->discountPercentage / 100) : 0;
    }

    #[Computed]
    public function afterDiscountAmount(): float
    {
        return $this->dppAmount - $this->invoiceDiscountAmount;
    }

    #[Computed]
    public function vatAmount(): float
    {
        return $this->includeVat ? ($this->afterDiscountAmount * $this->vatPercentage / 100) : 0;
    }

    #[Computed]
    public function grandTotal(): float
    {
        return $this->afterDiscountAmount + $this->vatAmount;
    }

    public function render()
    {
        return view('pm::livewire.project.invoice-detail');
    }
}
