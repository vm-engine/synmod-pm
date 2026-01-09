<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Form\ClientFormObject;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

class ClientForm extends Component
{
    public ClientFormObject $form;

    public function mount(?int $id = null)
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
    }

    public function save()
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

    public function render()
    {
        // For synapse-select (key => value format)
        $categories = ClientCategory::pluck('name', 'id')->toArray();

        // For synapse-adv-select (array with value/label)
        $services = collect(Services::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();

        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::client.list_title'),
            url: backend_route('pm.client.index'),
            icon: 'fa-solid fa-users'
        )->add(
            label: $this->form->client ? __('pm::client.edit_title') : __('pm::client.create_title'),
            icon: 'fa-solid fa-pen'
        );

        synav()->setActiveMenu('pm.client.index');

        return view('pm::livewire.client-form', [
            'categories' => $categories,
            'services' => $services,
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title($this->form->client ? __('pm::client.edit_title') : __('pm::client.create_title')));
    }
}
