<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\SignatureFormObject;
use VmEngine\Pm\Models\Signature;

new class extends Component
{
    use WithFileUploads;

    public SignatureFormObject $form;

    #[On('load-signature')]
    public function loadSignature($id): void
    {
        try {
            $this->resetForm();
            $signature = Signature::query()->findOrFail($id);
            $this->form->setSignature($signature);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::signature.not_found'),
            );
            $this->dispatch('close-modal-signature-form');
        }
    }

    #[On('close-modal-signature-form')]
    #[On('reset-signature-form')]
    public function resetForm(): void
    {
        $this->dispatch('dragndrop-clear-input');
        $this->form->reset();
    }

    public function save(): void
    {
        if ($this->form->signature) {
            $this->form->update();
            $message = __('pm::signature.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::signature.created_successfully');
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: $message,
        );

        $this->dispatch('dragndrop-clear-input');
        $this->dispatch('close-modal-signature-form');
        $this->dispatch('signature-refresh');
    }
};
