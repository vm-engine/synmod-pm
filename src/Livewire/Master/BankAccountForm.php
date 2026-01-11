<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Master;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\BankAccountFormObject;
use VmEngine\Pm\Models\BankAccount;

class BankAccountForm extends Component
{
    use WithFileUploads;

    public BankAccountFormObject $form;

    protected $listeners = [
        'load-bank-account' => 'loadAccount',
        'reset-bank-account-form' => 'resetForm',
    ];

    public function loadAccount($id)
    {
        try {
            $this->resetForm();
            $bankAccount = BankAccount::query()->findOrFail($id);
            $this->form->setAccount($bankAccount);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => __('pm::bank_account.not_found'),
            ]);
            $this->dispatch('close-modal-bank-account-form');
        }
    }

    #[On('close-modal-bank-account-form')]
    public function resetForm()
    {
        $this->dispatch('dragndrop-clear-input');
        $this->form->reset();
    }

    public function save()
    {
        if ($this->form->bankAccount) {
            $this->form->update();
            $message = __('pm::bank_account.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::bank_account.created_successfully');
        }

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);

        $this->dispatch('dragndrop-clear-input');
        $this->dispatch('close-modal-bank-account-form');
        $this->dispatch('bank-account-refresh');
    }

    public function render()
    {
        return view('pm::livewire.master.bank-account-form');
    }
}
