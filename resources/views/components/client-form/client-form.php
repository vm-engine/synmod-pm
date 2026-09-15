<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Form\ClientFormObject;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    use WithFileUploads;

    public ClientFormObject $form;

    public function mount(?int $id = null): void
    {
        if ($id) {
            try {
                $client = Client::findOrFail($id);
                $this->form->setClient($client);
            } catch (ModelNotFoundException) {
                session()->flash('danger', __('pm::messages.client_not_found'));
                $this->redirectRoute(backend_route('pm.client.index', nameOnly: true), navigate: true);
            }
        }

        synav()->setActiveMenu('pm.client.index');
    }

    public function title(): string
    {
        return $this->form->client ? __('pm::client.edit_title') : __('pm::client.create_title');
    }

    public function save(): void
    {
        if ($this->form->client) {
            $this->form->update();
            session()->flash('success', __('pm::messages.client_updated_successfully'));
        } else {
            $this->form->store();
            session()->flash('success', __('pm::messages.client_created_successfully'));
        }

        $this->redirectRoute(backend_route('pm.client.index', nameOnly: true), navigate: true);
    }

    #[Computed()]
    public function categories(): array
    {
        return ClientCategory::select('id as value', 'name as label')->get()->toArray();
    }

    #[Computed()]
    public function services(): array
    {
        return collect(Services::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::client.list_title'),
            url: backend_route('pm.client.index'),
            icon: 'fa-solid fa-users',
        )->add(
            label: $this->form->client ? __('pm::client.edit_title') : __('pm::client.create_title'),
            icon: 'fa-solid fa-pen',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
