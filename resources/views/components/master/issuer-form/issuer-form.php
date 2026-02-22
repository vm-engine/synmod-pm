<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\IssuerFormObject;
use VmEngine\Pm\Models\DocumentIssuer;

new class extends Component
{
    use WithFileUploads;

    public IssuerFormObject $form;

    #[On('load-issuer')]
    public function loadIssuer($id): void
    {
        try {
            $this->resetForm();
            $issuer = DocumentIssuer::query()->findOrFail($id);
            $this->form->setIssuer($issuer);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::issuer.not_found'),
            );
            $this->dispatch('close-modal-issuer-form');
        }
    }

    #[On('close-modal-issuer-form')]
    #[On('reset-issuer-form')]
    public function resetForm(): void
    {
        $this->dispatch('dragndrop-clear-input');
        $this->form->reset();
    }

    public function save(): void
    {
        if ($this->form->issuer) {
            $this->form->update();
            $message = __('pm::issuer.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::issuer.created_successfully');
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: $message,
        );

        $this->dispatch('dragndrop-clear-input');
        $this->dispatch('close-modal-issuer-form');
        $this->dispatch('issuer-refresh');
    }
};
