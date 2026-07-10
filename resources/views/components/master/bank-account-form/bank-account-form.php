<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\BankAccountFormObject;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Services\BankAccountService;

new class extends Component
{
    use WithFileUploads;

    public BankAccountFormObject $form;

    #[Computed]
    public function bankNameSuggestions(): array
    {
        return app(BankAccountService::class)->bankNameSuggestions();
    }

    #[On('load-bank-account')]
    public function loadAccount($id): void
    {
        try {
            $this->resetForm();
            $bankAccount = BankAccount::query()->findOrFail($id);
            $this->form->setAccount($bankAccount);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::bank_account.not_found'),
            );
            $this->dispatch('close-modal-bank-account-form');
        }
    }

    #[On('close-modal-bank-account-form')]
    #[On('reset-bank-account-form')]
    public function resetForm(): void
    {
        $this->dispatch('dragndrop-clear-input');
        $this->form->reset();
    }

    public function save(): void
    {
        if ($this->form->bankAccount) {
            $this->form->update();
            $message = __('pm::bank_account.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::bank_account.created_successfully');
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: $message,
        );

        $this->dispatch('dragndrop-clear-input');
        $this->dispatch('close-modal-bank-account-form');
        $this->dispatch('bank-account-refresh');
    }
};
