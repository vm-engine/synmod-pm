<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire;

use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ClientList extends Component
{
    use WithPagination;

    #[Url()]
    public $q;

    #[Url()]
    public array $filterCategories = [];

    #[Url()]
    public array $filterServices = [];

    #[Url()]
    public string $filterStatus = '';

    #[Url()]
    public string $filterActive = '';

    public int $limit = 10;

    public string $sort = 'id';

    public string $sortDirection = 'desc';

    public function updated($property)
    {
        $this->resetPage();
    }

    public function updatedFilterCategories($value)
    {
        // Ensure empty arrays are properly handled
        if (empty($value)) {
            $this->filterCategories = [];
        }
        $this->resetPage();
    }

    public function updatedFilterServices($value)
    {
        // Ensure empty arrays are properly handled
        if (empty($value)) {
            $this->filterServices = [];
        }
        $this->resetPage();
    }

    public function sortData($sort)
    {
        if ($this->sort === $sort && $this->sortDirection === 'asc') {
            $this->sortDirection = 'desc';
        } elseif ($this->sort === $sort && $this->sortDirection === 'desc') {
            $this->reset('sort', 'sortDirection');

            return;
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sort = $sort;
    }

    public function filterByCategory($categoryId)
    {
        // Toggle category in filter
        if (in_array($categoryId, $this->filterCategories)) {
            $this->filterCategories = array_values(array_diff($this->filterCategories, [$categoryId]));
        } else {
            $this->filterCategories[] = $categoryId;
        }
        $this->resetPage();
    }

    public function filterByService($serviceId)
    {
        // Toggle service in filter
        if (in_array($serviceId, $this->filterServices)) {
            $this->filterServices = array_values(array_diff($this->filterServices, [$serviceId]));
        } else {
            $this->filterServices[] = $serviceId;
        }
        $this->resetPage();
    }

    public function toggleActive(int $clientId): void
    {
        try {
            DB::transaction(function () use ($clientId) {
                $client = Client::findOrFail($clientId);
                $client->active = ! $client->active;
                $client->save();

                // Update related user (only active if client is both active status AND active)
                $client->user->update([
                    'status' => $client->status === Client::STATUS_ACTIVE && $client->active,
                ]);
            });

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::messages.client_status_updated'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($token)
    {
        try {
            $id = Client::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $client = Client::findOrFail($id);

            DB::transaction(function () use ($client) {
                // Delete client first (user will cascade or be handled separately)
                $client->delete();
            });

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::messages.client_deleted_successfully'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => __('pm::messages.error'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[Computed()]
    public function clientList()
    {
        $query = Client::with('category', 'user', 'projectCategories');

        // Apply search
        if (! empty($this->q) && strlen($this->q) > 2) {
            $query->search($this->q);
        }

        // Apply category filter
        if (! empty($this->filterCategories)) {
            $query->whereIn('client_category_id', $this->filterCategories);
        }

        // Apply services filter (ANY match)
        if (! empty($this->filterServices)) {
            $query->byServiceAny($this->filterServices);
        }

        // Apply status filter
        if ($this->filterStatus === 'prospect') {
            $query->where('status', Client::STATUS_PROSPECT);
        } elseif ($this->filterStatus === 'active') {
            $query->where('status', Client::STATUS_ACTIVE);
        }

        // Apply active filter
        if ($this->filterActive === 'active') {
            $query->where('active', true);
        } elseif ($this->filterActive === 'inactive') {
            $query->where('active', false);
        }

        return $query
            ->orderBy($this->sort, $this->sortDirection)
            ->paginate($this->limit)
            ->onEachSide(1);
    }

    public function render()
    {
        $categories = ClientCategory::select('id as value', 'name as label')->get()->toArray();
        $services = collect(Services::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();

        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::client.list_title'),
            icon: 'fa-solid fa-users',
        );

        synav()->setActiveMenu('pm.client.index');

        return view('pm::livewire.client-list', [
            'categories' => $categories,
            'services' => $services,
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::client.list_title')));
    }
}
