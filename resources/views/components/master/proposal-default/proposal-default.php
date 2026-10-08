<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Form\Concerns\EditsProposalSections;
use VmEngine\Pm\Models\ProposalTypeDefault;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    use EditsProposalSections;

    public int $type = 1;

    /** @var list<array<string, mixed>> */
    public array $costing = [];

    /** @var list<array<string, mixed>> */
    public array $hosting = [];

    /** @var list<array<string, mixed>> */
    public array $deliverables = [];

    /** @var list<array<string, mixed>> */
    public array $afterSales = [];

    /** Start date only drives the Start/Finish preview columns; it is not stored. */
    public array $timeline = ['start_date' => '', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []];

    /** Unused here; declared because the shared trait's list switch references them. */
    public array $features = [];

    public array $scope = ['goal' => '', 'menu' => [], 'mockups' => []];

    public function mount(): void
    {
        $this->selectType(ProposalType::Website->value);
        synav()->setActiveMenu('pm.master.proposal_default');
    }

    public function title(): string
    {
        return __('pm::proposal.defaults_title');
    }

    public function selectType(int $type): void
    {
        $this->type = (ProposalType::tryFrom($type) ?? abort(400))->value;
        $sections = ProposalTypeDefault::query()->where('type', $this->type)->first()?->sections()
            ?? ProposalDefaults::for(ProposalType::from($this->type));
        $this->loadSections($sections['costing'], $sections['hosting'], $sections['deliverables'], $sections['after_sales']);
        $this->timeline = [
            'start_date' => CarbonImmutable::now()->toDateString(),
            'extra_holidays' => [],
            'worked_holidays' => [],
            'tasks' => GanttScheduler::normalize($sections['timeline_tasks']),
        ];
        unset($this->grandTotal, $this->scheduledTasks);
    }

    public function save(): void
    {
        $this->validate(['timeline.tasks.*.duration' => 'required|integer|min:1|max:1000']);
        $sections = $this->sectionsForStorage();
        ProposalTypeDefault::query()->updateOrCreate(['type' => $this->type], [
            'costing' => $sections['costing'],
            'hosting' => $sections['hosting'],
            'timeline_tasks' => $sections['timeline_tasks'],
            'deliverables' => $sections['deliverables'],
            'after_sales' => $sections['after_sales'],
            'updated_by' => Auth::id(),
        ]);
        SynAuth::logActivity(Auth::id(), 'pm.master.proposal_default.update', 'Updated proposal defaults for type '.$this->type, 'master.proposal_default', 'pm');
        $this->dispatch('notify', variant: 'success', title: __('pm::proposal.defaults_title'), message: __('pm::proposal.master_saved'));
    }

    #[Computed]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(label: __('pm::menu.be.parent'), icon: 'fa-solid fa-list')
            ->add(label: __('pm::menu.be.master.parent'), icon: 'fa-solid fa-table')
            ->add(label: __('pm::menu.be.master.proposal_default'), icon: 'fa-solid fa-sliders');
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
