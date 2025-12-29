<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Exception;
use Illuminate\Support\Facades\DB;
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
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ProjectList extends Component
{
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

    public int $limit = 10;

    public string $sort = 'id';

    public string $sortDirection = 'desc';

    public function updated($property)
    {
        $this->resetPage();
    }

    public function updatedFilterDivisions($value)
    {
        if (empty($value)) {
            $this->filterDivisions = [];
        }
        $this->resetPage();
    }

    public function updatedFilterClients($value)
    {
        if (empty($value)) {
            $this->filterClients = [];
        }
        $this->resetPage();
    }

    public function updatedFilterServices($value)
    {
        if (empty($value)) {
            $this->filterServices = [];
        }
        $this->resetPage();
    }

    public function updatedFilterStatuses($value)
    {
        if (empty($value)) {
            $this->filterStatuses = [];
        }
        $this->resetPage();
    }

    public function updatedFilterPriorities($value)
    {
        if (empty($value)) {
            $this->filterPriorities = [];
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

    public function filterByDivision($divisionId)
    {
        if (in_array($divisionId, $this->filterDivisions)) {
            $this->filterDivisions = array_values(array_diff($this->filterDivisions, [$divisionId]));
        } else {
            $this->filterDivisions[] = $divisionId;
        }
        $this->resetPage();
    }

    public function filterByClient($clientId)
    {
        if (in_array($clientId, $this->filterClients)) {
            $this->filterClients = array_values(array_diff($this->filterClients, [$clientId]));
        } else {
            $this->filterClients[] = $clientId;
        }
        $this->resetPage();
    }

    public function filterByService($serviceId)
    {
        if (in_array($serviceId, $this->filterServices)) {
            $this->filterServices = array_values(array_diff($this->filterServices, [$serviceId]));
        } else {
            $this->filterServices[] = $serviceId;
        }
        $this->resetPage();
    }

    public function filterByStatus($statusId)
    {
        if (in_array($statusId, $this->filterStatuses)) {
            $this->filterStatuses = array_values(array_diff($this->filterStatuses, [$statusId]));
        } else {
            $this->filterStatuses[] = $statusId;
        }
        $this->resetPage();
    }

    public function filterByPriority($priorityId)
    {
        if (in_array($priorityId, $this->filterPriorities)) {
            $this->filterPriorities = array_values(array_diff($this->filterPriorities, [$priorityId]));
        } else {
            $this->filterPriorities[] = $priorityId;
        }
        $this->resetPage();
    }

    public function delete($token)
    {
        try {
            $id = Project::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }

            $project = Project::findOrFail($id);

            DB::transaction(function () use ($project) {
                $project->delete();
            });

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => __('pm::messages.success'),
                'message' => __('pm::messages.project_deleted_successfully'),
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
    public function projectList()
    {
        $query = Project::with(['division', 'client', 'user', 'projectManager', 'documents'])
            ->withCount('documents');

        // SECURITY: If user has client role, only show their own projects
        $user = auth()->user();
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
        if ($this->filterYear) {
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

    public function render()
    {
        $divisions = Division::select('id as value', 'name as label')->get()->toArray();
        $clients = Client::select('id as value', 'company as label')->get()->toArray();
        $services = collect(Services::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
        $statuses = collect(ProjectStatus::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
        $priorities = collect(ProjectPriority::cases())->map(fn ($p) => [
            'value' => $p->value,
            'label' => $p->label(),
        ])->toArray();

        // Generate year options (current year ± 5 years)
        $currentYear = (int) date('Y');
        $years = [];
        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
            $years[] = ['value' => $i, 'label' => (string) $i];
        }

        $progressRanges = [
            ['value' => '0-25', 'label' => '0-25%'],
            ['value' => '26-50', 'label' => '26-50%'],
            ['value' => '51-75', 'label' => '51-75%'],
            ['value' => '76-100', 'label' => '76-100%'],
        ];

        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            icon: 'fa-solid fa-diagram-project',
        );

        synav()->setActiveMenu('pm.project.index');

        return view('pm::livewire.project.project-list', [
            'divisions' => $divisions,
            'clients' => $clients,
            'services' => $services,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'years' => $years,
            'progressRanges' => $progressRanges,
            'breadcrumbs' => $breadcrumbs,
        ])->title(__('pm::project.list_title'));
    }
}
