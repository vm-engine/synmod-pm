<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Livewire\Form;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Services\InvoiceService;

class InvoiceFormObject extends Form
{
    public ?ProjectInvoice $invoice = null;

    public ?Project $project = null;

    public ?int $project_id = null;

    public ?int $project_category_id = null;

    public ?int $client_id = null;

    public ?int $spk_document_id = null;

    public string $spk_number = '';

    public string $invoice_number = '';

    public string $recipient = '';

    public string $address = '';

    public ?string $issue_date = null;

    public ?string $due_date = null;

    public string $attention = '';

    public string $notes = '';

    public ?int $issuer_id = null;

    public ?int $bank_account_id = null;

    public bool $has_signature = false;

    public ?int $signature_id = null;

    public function setProject(Project $project): void
    {
        $this->project = $project;
        $this->project_id = $project->id;
        $this->project_category_id = $project->project_category_id;
        $this->client_id = $project->client_id;

        // Get latest SPK document
        $latestSpk = $project->documents()
            ->byType(Documents::SPK)
            ->latestVersion()
            ->first();

        if ($latestSpk) {
            $this->spk_document_id = $latestSpk->id;
            $this->spk_number = $latestSpk->number;
        }

        // Auto-fill from client
        $project->loadMissing('client');
        if ($project->client) {
            $this->recipient = $project->client->company ?? '';
            $this->address = $project->client->address ?? '';
            $this->attention = $project->client->pic ?? '';
        }

        // Generate invoice number
        $invoiceService = app(InvoiceService::class);
        $this->invoice_number = $invoiceService->generateInvoiceNumber($project);

        // Set defaults from DbConfig (fall back to literals if rows missing).
        // dbconf returns string values when read from the DB (ints only survive
        // in-process via its cache), so nullable ID fields must be cast explicitly.
        $dueDays = (int) dbconf('pm.invoice.payment_due_days', 14);
        $issuerId = dbconf('pm.invoice.default_issuer_id', null);
        $bankAccountId = dbconf('pm.invoice.default_bank_account_id', null);
        $signatureId = dbconf('pm.invoice.default_signature_id', null);

        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays($dueDays)->format('Y-m-d');
        $this->issuer_id = $issuerId !== null && $issuerId !== '' ? (int) $issuerId : null;
        $this->bank_account_id = $bankAccountId !== null && $bankAccountId !== '' ? (int) $bankAccountId : null;
        $this->signature_id = $signatureId !== null && $signatureId !== '' ? (int) $signatureId : null;
    }

    public function setInvoice(ProjectInvoice $invoice): void
    {
        $this->invoice = $invoice;
        $this->project = $invoice->project;
        $this->project_id = $invoice->project_id;
        $this->project_category_id = $invoice->project_category_id;
        $this->client_id = $invoice->client_id;
        $this->spk_document_id = $invoice->spk_document_id;

        // Get SPK number
        if ($invoice->spkDocument) {
            $this->spk_number = $invoice->spkDocument->number;
        }

        $this->invoice_number = $invoice->invoice_number;
        $this->recipient = $invoice->recipient ?? '';
        $this->address = $invoice->address ?? '';
        $this->issue_date = $invoice->issue_date?->format('Y-m-d');
        $this->due_date = $invoice->due_date?->format('Y-m-d');
        $this->attention = $invoice->attention ?? '';
        $this->notes = $invoice->notes ?? '';
        $this->issuer_id = $invoice->issuer_id;
        $this->bank_account_id = $invoice->bank_account_id;
        $this->has_signature = (bool) $invoice->has_signature;
        $this->signature_id = $invoice->signature_id;
    }

    public function rules(): array
    {
        return [
            'invoice_number' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'address' => 'required|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'attention' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'issuer_id' => 'required|exists:document_issuers,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'has_signature' => 'boolean',
            'signature_id' => 'required_if:has_signature,true|nullable|exists:signatures,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'invoice_number.required' => __('pm::invoice.validation.invoice_number_required'),
            'recipient.required' => __('pm::invoice.validation.recipient_required'),
            'address.required' => __('pm::invoice.validation.address_required'),
            'issue_date.required' => __('pm::invoice.validation.issue_date_required'),
            'issue_date.date' => __('pm::invoice.validation.issue_date_date'),
            'due_date.required' => __('pm::invoice.validation.due_date_required'),
            'due_date.date' => __('pm::invoice.validation.due_date_date'),
            'due_date.after_or_equal' => __('pm::invoice.validation.due_date_after'),
            'issuer_id.required' => __('pm::invoice.validation.issuer_required'),
            'issuer_id.exists' => __('pm::invoice.validation.issuer_exists'),
            'bank_account_id.required' => __('pm::invoice.validation.bank_account_required'),
            'bank_account_id.exists' => __('pm::invoice.validation.bank_account_exists'),
            'signature_id.required_if' => __('pm::invoice.validation.signature_required'),
            'signature_id.exists' => __('pm::invoice.validation.signature_exists'),
        ];
    }

    public function store(): ProjectInvoice
    {
        $this->validate();

        $invoiceService = app(InvoiceService::class);

        $data = [
            'project_id' => $this->project_id,
            'project_category_id' => $this->project_category_id,
            'client_id' => $this->client_id,
            'spk_document_id' => $this->spk_document_id,
            'invoice_number' => $this->invoice_number,
            'recipient' => $this->recipient,
            'address' => $this->address,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'attention' => $this->attention,
            'notes' => $this->notes,
            'issuer_id' => $this->issuer_id,
            'bank_account_id' => $this->bank_account_id,
            'has_signature' => $this->has_signature,
            'signature_id' => $this->signature_id,
        ];

        $invoice = $invoiceService->create($this->project, $data);

        $this->reset();

        return $invoice;
    }

    public function update(): ProjectInvoice
    {
        $this->validate();

        $invoiceService = app(InvoiceService::class);

        $data = [
            'invoice_number' => $this->invoice_number,
            'recipient' => $this->recipient,
            'address' => $this->address,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'attention' => $this->attention,
            'notes' => $this->notes,
            'issuer_id' => $this->issuer_id,
            'bank_account_id' => $this->bank_account_id,
            'has_signature' => $this->has_signature,
            'signature_id' => $this->signature_id,
        ];

        $this->invoice = $invoiceService->update($this->invoice, $data);

        return $this->invoice;
    }
}
