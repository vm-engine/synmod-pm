<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Project;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ProjectDetail extends Component
{
    public Project $project;

    public string $activeTab = 'documents';

    public function mount(int $id)
    {
        try {
            $this->project = Project::with([
                'division',
                'client',
                'user',
                'projectManager',
                'creator',
                'updater',
            ])->findOrFail($id);
        } catch (ModelNotFoundException) {
            session()->flash('danger', __('pm::messages.project_not_found'));
            $this->redirectRoute(backend_route('pm.project.index', nameOnly: true), navigate: true);
        }
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    #[On('document-uploaded')]
    public function refreshProject(): void
    {
        // Refresh the project with all relationships
        $this->project->refresh();
        $this->project->load([
            'division',
            'client',
            'user',
            'projectManager',
            'creator',
            'updater',
        ]);
    }

    #[Computed()]
    public function projectTimeline()
    {
        // Placeholder for future activity log
        // Will integrate with spatie/laravel-activitylog when implemented
        return collect([]);
    }

    #[Computed()]
    public function financialSummary()
    {
        $budget = $this->project->budget ?? 0;
        $nominal = $this->project->nominal ?? 0;
        $downPayment = $this->project->down_payment ?? 0;

        return [
            'budget' => $budget,
            'nominal' => $nominal,
            'down_payment' => $downPayment,
            'remaining' => $nominal - $downPayment,
            'budget_variance' => $budget - $nominal,
            'budget_percentage' => $budget > 0 ? round(($nominal / $budget) * 100, 2) : 0,
            'payment_percentage' => $nominal > 0 ? round(($downPayment / $nominal) * 100, 2) : 0,
        ];
    }

    #[Computed()]
    public function teamMembers()
    {
        if (empty($this->project->assigned_team)) {
            return collect([]);
        }

        $UserModel = config('auth.providers.users.model');

        return $UserModel::whereIn('id', $this->project->assigned_team)->get();
    }

    public function render()
    {
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            url: backend_route('pm.project.index'),
            icon: 'fa-solid fa-diagram-project'
        )->add(
            label: $this->project->name,
            icon: 'fa-solid fa-eye'
        );

        synav()->setActiveMenu('pm.project.index');

        return view('pm::livewire.project.project-detail', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(__('pm::project.detail_title').': '.$this->project->name);
    }
}
