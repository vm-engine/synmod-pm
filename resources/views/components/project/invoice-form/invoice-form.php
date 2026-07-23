<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Form\InvoiceFormObject;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\CurrencyFormatter;

new class extends Component
{
    public InvoiceFormObject $form;

    public ?int $projectId = null;

    public int $formNonce = 0;

    #[On('create-invoice')]
    public function createInvoice(int $projectId): void
    {
        try {
            $this->resetForm();
            $this->projectId = $projectId;
            $project = Project::findOrFail($projectId);
            $this->form->setProject($project);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: __('pm::messages.error'),
                message: __('pm::project.not_found'),
            );
            $this->dispatch('close-modal-invoice-form');
        }
    }

    #[On('load-invoice')]
    public function loadInvoice(int $id): void
    {
        try {
            $this->resetForm();
            $invoice = ProjectInvoice::with(['project', 'spkDocument'])->findOrFail($id);
            $this->projectId = $invoice->project_id;
            $this->form->setInvoice($invoice);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: __('pm::messages.error'),
                message: __('pm::invoice.not_found'),
            );
            $this->dispatch('close-modal-invoice-form');
        }
    }

    #[On('close-modal-invoice-form')]
    #[On('reset-invoice-form')]
    public function resetForm(): void
    {
        // Bump the nonce so the issuer/bank/signature/currency adv-selects are
        // re-keyed and re-initialised, clearing any stale Alpine display state.
        $this->formNonce++;
        $this->form->reset();
        $this->projectId = null;
    }

    public function save(): void
    {
        if ($this->form->invoice) {
            $this->form->update();
            $message = __('pm::invoice.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::invoice.created_successfully');
        }

        $this->dispatch('notify',
            variant: 'success',
            title: __('pm::messages.success'),
            message: $message,
        );

        $this->dispatch('close-modal-invoice-form');
        $this->dispatch('invoice-refresh');
        $this->dispatch('activity-refresh');
    }

    #[Computed]
    public function currencyOptions(): array
    {
        return app(CurrencyFormatter::class)->options();
    }

    #[Computed]
    public function issuers(): array
    {
        return DocumentIssuer::select('id', 'name', 'tax_id_number')
            ->orderBy('name')
            ->get()
            ->map(fn ($issuer) => [
                'value' => $issuer->id,
                'label' => $issuer->name.' ('.$issuer->tax_id_number.')',
            ])
            ->toArray();
    }

    #[Computed]
    public function bankAccounts(): array
    {
        return BankAccount::select('id', 'name', 'number', 'bank_name')
            ->orderBy('name')
            ->get()
            ->map(fn ($account) => [
                'value' => $account->id,
                'label' => $account->name.' - '.$account->number.' ('.$account->bank_name.')',
            ])
            ->toArray();
    }

    #[Computed]
    public function signatures(): array
    {
        return Signature::select('id', 'name', 'position')
            ->orderBy('name')
            ->get()
            ->map(fn ($signature) => [
                'value' => $signature->id,
                'label' => $signature->name.' - '.$signature->position,
            ])
            ->toArray();
    }
};
