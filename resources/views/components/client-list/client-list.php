<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Pm\Services\ClientService;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\Synapse\Traits\RemembersQueryParams;

new class extends Component
{
    use RemembersQueryParams;
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

    #[Url()]
    public int $limit = 10;

    #[Url()]
    public string $sort = 'id';

    #[Url()]
    public string $sortDirection = 'desc';

    public string $npwpImage = '';

    protected function rememberedParams(): array
    {
        return ['q', 'filterCategories', 'filterServices', 'filterStatus', 'filterActive', 'limit', 'sort', 'sortDirection'];
    }

    protected function rememberKey(): string
    {
        return 'synapse:query_params:pm.client-list';
    }

    public function mount(): void
    {
        synav()->setActiveMenu('pm.client.index');
    }

    public function title(): string
    {
        return __('pm::client.list_title');
    }

    public function updated($property): void
    {
        $this->resetPage();
    }

    #[On('close-modal-npwp-image')]
    public function resetNpwpImage(): void
    {
        $this->npwpImage = '';
    }

    public function showImage($id): void
    {
        try {
            $client = Client::findOrFail($id);
            $npwpImage = Storage::disk('local')->get($client->npwp_image);
            $this->npwpImage = 'data:image/png;base64,'.base64_encode($npwpImage);
            $this->dispatch('open-modal-npwp-image');
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'error',
                title: __('pm::messages.error'),
                message: $e->getMessage(),
            );
        }
    }

    public function updatedFilterCategories($value): void
    {
        if (empty($value)) {
            $this->filterCategories = [];
        }
        $this->resetPage();
    }

    public function updatedFilterServices($value): void
    {
        if (empty($value)) {
            $this->filterServices = [];
        }
        $this->resetPage();
    }

    public function sortData($sort): void
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

    public function filterByCategory($categoryId): void
    {
        if (in_array($categoryId, $this->filterCategories)) {
            $this->filterCategories = array_values(array_diff($this->filterCategories, [$categoryId]));
        } else {
            $this->filterCategories[] = $categoryId;
        }
        $this->resetPage();
    }

    public function filterByService($serviceId): void
    {
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
            $client = Client::findOrFail($clientId);
            $clientService = app(ClientService::class);

            $clientService->toggleActive($client);

            $this->dispatch('notify',
                variant: 'success',
                title: __('pm::messages.success'),
                message: __('pm::messages.client_status_updated'),
            );
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: __('pm::messages.error'),
                message: $e->getMessage(),
            );
        }
    }

    public function delete($token): void
    {
        try {
            $id = Client::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $client = Client::findOrFail($id);
            $clientService = app(ClientService::class);

            $clientService->delete($client, deleteUser: true);

            $this->dispatch('synapse-confirmed');
            $this->dispatch('notify',
                variant: 'success',
                title: __('pm::messages.success'),
                message: __('pm::messages.client_deleted_successfully'),
            );
        } catch (Exception $e) {
            $this->dispatch('synapse-confirmed');
            $this->dispatch('notify',
                variant: 'danger',
                title: __('pm::messages.error'),
                message: $e->getMessage(),
            );
        }
    }

    #[Computed()]
    public function clientList()
    {
        $query = Client::with('category', 'user', 'projectCategories');

        if (! empty($this->q) && strlen($this->q) > 2) {
            $query->search($this->q);
        }

        if (! empty($this->filterCategories)) {
            $query->whereIn('client_category_id', $this->filterCategories);
        }

        if (! empty($this->filterServices)) {
            $query->byServiceAny($this->filterServices);
        }

        if ($this->filterStatus === 'prospect') {
            $query->where('status', Client::STATUS_PROSPECT);
        } elseif ($this->filterStatus === 'active') {
            $query->where('status', Client::STATUS_ACTIVE);
        }

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
            icon: 'fa-solid fa-users',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
