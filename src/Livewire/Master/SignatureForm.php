<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Master;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\SignatureFormObject;
use VmEngine\Pm\Models\Signature;

class SignatureForm extends Component
{
    use WithFileUploads;

    public SignatureFormObject $form;

    protected $listeners = [
        'load-signature' => 'loadSignature',
        'reset-signature-form' => 'resetForm',
    ];

    public function loadSignature($id)
    {
        try {
            $this->resetForm();
            $signature = Signature::query()->findOrFail($id);
            $this->form->setSignature($signature);
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => __('pm::signature.not_found'),
            ]);
            $this->dispatch('close-modal-signature-form');
        }
    }

    #[On('close-modal-signature-form')]
    public function resetForm()
    {
        $this->dispatch('dragndrop-clear-input');
        $this->form->reset();
    }

    public function save()
    {
        if ($this->form->signature) {
            $this->form->update();
            $message = __('pm::signature.updated_successfully');
        } else {
            $this->form->store();
            $message = __('pm::signature.created_successfully');
        }

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Success',
            'message' => $message,
        ]);

        $this->dispatch('dragndrop-clear-input');
        $this->dispatch('close-modal-signature-form');
        $this->dispatch('signature-refresh');
    }

    public function render()
    {
        return view('pm::livewire.master.signature-form');
    }
}
