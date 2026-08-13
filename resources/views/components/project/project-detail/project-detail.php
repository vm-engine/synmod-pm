<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use VmEngine\Pm\Models\Project;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    public Project $project;

    #[Url(as: 'tab')]
    public string $activeTab = 'documents';

    public function mount(int $id): void
    {
        try {
            $this->project = Project::with([
                'division',
                'projectCategory',
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

        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return __('pm::project.detail_title').': '.$this->project->name;
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
        return collect([]);
    }

    #[Computed()]
    public function financialSummary(): array
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

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            url: backend_route('pm.project.index'),
            icon: 'fa-solid fa-diagram-project',
        )->add(
            label: $this->project->name,
            icon: 'fa-solid fa-eye',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
