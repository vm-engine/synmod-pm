<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\ProjectService;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\Synapse\Traits\RemembersQueryParams;

new class extends Component
{
    use RemembersQueryParams;
    use WithPagination;

    #[Url()]
    public $q;

    #[Url()]
    public array $filterDivisions = [];

    #[Url()]
    public array $filterClients = [];

    #[Url()]
    public array $filterServices = [];

    #[Url()]
    public array $filterStatuses = [];

    #[Url()]
    public array $filterPriorities = [];

    #[Url()]
    public string $filterYear = '';

    #[Url()]
    public string $filterProgress = '';

    #[Url()]
    public int $limit = 12;

    #[Url()]
    public string $sort = 'id';

    #[Url()]
    public string $sortDirection = 'desc';

    protected function rememberedParams(): array
    {
        return ['q', 'filterDivisions', 'filterClients', 'filterServices', 'filterStatuses', 'filterPriorities', 'filterYear', 'filterProgress', 'limit', 'sort', 'sortDirection'];
    }

    protected function rememberKey(): string
    {
        return 'synapse:query_params:pm.project-list';
    }

    public function mount(): void
    {
        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return __('pm::project.list_title');
    }

    public function updated($property): void
    {
        $this->resetPage();
    }

    public function updatedFilterDivisions($value): void
    {
        if (empty($value)) {
            $this->filterDivisions = [];
        }
        $this->resetPage();
    }

    public function updatedFilterClients($value): void
    {
        if (empty($value)) {
            $this->filterClients = [];
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

    public function updatedFilterStatuses($value): void
    {
        if (empty($value)) {
            $this->filterStatuses = [];
        }
        $this->resetPage();
    }

    public function updatedFilterPriorities($value): void
    {
        if (empty($value)) {
            $this->filterPriorities = [];
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

    public function filterByDivision($divisionId): void
    {
        if (in_array($divisionId, $this->filterDivisions)) {
            $this->filterDivisions = array_values(array_diff($this->filterDivisions, [$divisionId]));
        } else {
            $this->filterDivisions[] = $divisionId;
        }
        $this->resetPage();
    }

    public function filterByClient($clientId): void
    {
        if (in_array($clientId, $this->filterClients)) {
            $this->filterClients = array_values(array_diff($this->filterClients, [$clientId]));
        } else {
            $this->filterClients[] = $clientId;
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

    public function filterByStatus($statusId): void
    {
        if (in_array($statusId, $this->filterStatuses)) {
            $this->filterStatuses = array_values(array_diff($this->filterStatuses, [$statusId]));
        } else {
            $this->filterStatuses[] = $statusId;
        }
        $this->resetPage();
    }

    public function filterByPriority($priorityId): void
    {
        if (in_array($priorityId, $this->filterPriorities)) {
            $this->filterPriorities = array_values(array_diff($this->filterPriorities, [$priorityId]));
        } else {
            $this->filterPriorities[] = $priorityId;
        }
        $this->resetPage();
    }

    public function delete($token): void
    {
        try {
            $id = Project::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $project = Project::findOrFail($id);
            $projectService = app(ProjectService::class);

            $projectService->delete($project);

            $this->dispatch('synapse-confirmed');
            $this->dispatch('notify',
                variant: 'success',
                title: __('pm::messages.success'),
                message: __('pm::messages.project_deleted_successfully'),
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
    public function projectList()
    {
        $query = Project::with(['division', 'client', 'user', 'projectManager', 'documents'])
            ->withCount('documents');

        // SECURITY: If user has client role, only show their own projects
        $user = Auth::user();
        $clientRoleId = dbconf('pm.client_role_id');
        if ($clientRoleId && $user->roles()->where('roles.id', $clientRoleId)->exists()) {
            // Get client record for this user
            $client = Client::where('user_id', $user->id)->first();
            if ($client) {
                $query->where('client_id', $client->id);
            } else {
                // User has client role but no client record - show nothing
                $query->whereRaw('1 = 0');
            }
        }

        // Apply search
        if (! empty($this->q) && strlen($this->q) > 2) {
            $query->search($this->q);
        }

        // Apply division filter
        if (! empty($this->filterDivisions)) {
            $query->whereIn('division_id', $this->filterDivisions);
        }

        // Apply client filter
        if (! empty($this->filterClients)) {
            $query->whereIn('client_id', $this->filterClients);
        }

        // Apply services filter (ANY match)
        if (! empty($this->filterServices)) {
            $query->byServiceAny($this->filterServices);
        }

        // Apply status filter
        if (! empty($this->filterStatuses)) {
            $query->whereIn('status', $this->filterStatuses);
        }

        // Apply priority filter
        if (! empty($this->filterPriorities)) {
            $query->whereIn('priority', $this->filterPriorities);
        }

        // Apply year filter
        if (! empty($this->filterYear)) {
            $query->where('year', $this->filterYear);
        }

        // Apply progress filter
        if ($this->filterProgress) {
            $ranges = [
                '0-25' => [0, 25],
                '26-50' => [26, 50],
                '51-75' => [51, 75],
                '76-100' => [76, 100],
            ];
            if (isset($ranges[$this->filterProgress])) {
                [$min, $max] = $ranges[$this->filterProgress];
                $query->whereBetween('progress', [$min, $max]);
            }
        }

        return $query
            ->orderBy($this->sort, $this->sortDirection)
            ->paginate($this->limit)
            ->onEachSide(1);
    }

    #[Computed()]
    public function divisions(): array
    {
        return Division::select('id as value', 'name as label')->get()->toArray();
    }

    #[Computed()]
    public function clients(): array
    {
        return Client::select('id as value', 'company as label')->get()->toArray();
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
    public function statuses(): array
    {
        return collect(ProjectStatus::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
    }

    #[Computed()]
    public function priorities(): array
    {
        return collect(ProjectPriority::cases())->map(fn ($p) => [
            'value' => $p->value,
            'label' => $p->label(),
        ])->toArray();
    }

    #[Computed()]
    public function years(): array
    {
        return Project::select('year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year')
            ->map(fn ($y) => ['value' => (string) $y, 'label' => (string) $y])
            ->toArray();
    }

    #[Computed()]
    public function progressRanges(): array
    {
        return [
            ['value' => '0-25', 'label' => '0-25%'],
            ['value' => '26-50', 'label' => '26-50%'],
            ['value' => '51-75', 'label' => '51-75%'],
            ['value' => '76-100', 'label' => '76-100%'],
        ];
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            icon: 'fa-solid fa-diagram-project',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
