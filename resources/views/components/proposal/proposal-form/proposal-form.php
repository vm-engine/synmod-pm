<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Form\Concerns\EditsProposalSections;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\ProposalFactory;
use VmEngine\Pm\Services\Proposal\ProposalPublisher;
use VmEngine\Pm\Services\Proposal\ProposalValidator;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    use EditsProposalSections;
    use WithFileUploads;

    public Project $project;

    #[Locked]
    public ?int $proposalId = null;

    public int $type = 1;

    public string $title = '';

    public string $subject = '';

    public string $proposalDate = '';

    public bool $taxNote = true;

    public int $maintenanceMonths = 3;

    /** @var array{goal: string, menu: list<array<string, mixed>>, mockups: list<array{path: string, caption: string}>} */
    public array $scope = ['goal' => '', 'menu' => [], 'mockups' => []];

    /** @var list<array<string, mixed>> */
    public array $features = [];

    /** @var list<array<string, mixed>> */
    public array $costing = [];

    /** @var list<array<string, mixed>> */
    public array $hosting = [];

    /** @var array{start_date: string, extra_holidays: list<string>, worked_holidays: list<string>, tasks: list<array<string, mixed>>} */
    public array $timeline = ['start_date' => '', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []];

    /** @var list<array<string, mixed>> */
    public array $deliverables = [];

    /** @var list<array<string, mixed>> */
    public array $afterSales = [];

    /** @var array<int, TemporaryUploadedFile> */
    public array $mockupUploads = [];

    public function mount(int $id): void
    {
        $this->project = Project::with('client')->findOrFail($id);
        if ($this->project->proposal !== null) {
            $this->loadProposal($this->project->proposal);
        }

        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return __('pm::proposal.page_title');
    }

    #[Computed]
    public function proposal(): ?ProjectProposal
    {
        return $this->proposalId !== null ? ProjectProposal::query()->find($this->proposalId) : null;
    }

    public function create(int $type): void
    {
        abort_if($this->project->proposal()->exists(), 409);
        $this->loadProposal(app(ProposalFactory::class)->create($this->project, $this->proposalType($type)));
    }

    public function save(): void
    {
        $this->persist();
        $this->dispatch('notify', variant: 'success', title: __('pm::proposal.save'), message: __('pm::proposal.saved'));
    }

    /**
     * Save, validate and return the preview URL (null when invalid).
     * The browser opens it itself: a window.open() after this round-trip would be popup-blocked.
     */
    public function preview(string $format): ?string
    {
        abort_unless(in_array($format, ['pdf', 'pptx'], true), 400);
        $proposal = $this->persist();
        $this->tabErrors = app(ProposalValidator::class)->errors($proposal);
        if ($this->tabErrors !== []) {
            $title = $format === 'pdf' ? __('pm::proposal.preview_pdf') : __('pm::proposal.preview_pptx');
            $this->dispatch('notify', variant: 'danger', title: $title, message: __('pm::proposal.validation_failed'));

            return null;
        }

        return backend_route('pm.proposal.preview', ['id' => $this->project->id, 'format' => $format]);
    }

    public function publish(): void
    {
        $proposal = $this->persist();
        try {
            app(ProposalPublisher::class)->publish($proposal, (int) Auth::id());
        } catch (ProposalValidationException $e) {
            $this->tabErrors = $e->errors;
            $this->dispatch('notify', variant: 'danger', title: __('pm::proposal.publish'), message: __('pm::proposal.validation_failed'));

            return;
        }
        $this->tabErrors = [];
        unset($this->proposal);
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::proposal.publish'), message: __('pm::proposal.published'));
    }

    public function changeType(int $type): void
    {
        $proposal = app(ProposalFactory::class)->switchType($this->persist(), $this->proposalType($type));
        $this->loadProposal($proposal);
        $this->dispatch('synapse-confirmed');
    }

    public function addCustomFeature(): void
    {
        $sorts = array_column($this->features, 'sort');
        $this->features[] = [
            'id' => ProposalFactory::newRowId(),
            'item_id' => null,
            'name' => '',
            'description' => '',
            'checked' => true,
            'sort' => ($sorts === [] ? 0 : max($sorts)) + 10,
        ];
    }

    public function updatedMockupUploads(): void
    {
        $this->validate(['mockupUploads.*' => 'image|mimes:jpg,jpeg,png|max:5120']);
        foreach ($this->mockupUploads as $upload) {
            $this->scope['mockups'][] = [
                'path' => $upload->store('projects/'.$this->project->id.'/proposal/mockups', 'local'),
                'caption' => '',
            ];
        }
        $this->mockupUploads = [];
    }

    public function removeMockup(int $index): void
    {
        $path = $this->scope['mockups'][$index]['path'] ?? null;
        // The path comes from client-side state: only ever delete our own uploads
        if (is_string($path) && $this->isOwnMockup($path)) {
            Storage::disk('local')->delete($path);
        }
        unset($this->scope['mockups'][$index]);
        $this->scope['mockups'] = array_values($this->scope['mockups']);
    }

    #[Computed]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(label: __('pm::menu.be.parent'), icon: 'fa-solid fa-list')
            ->add(label: __('pm::project.list_title'), url: backend_route('pm.project.index'), icon: 'fa-solid fa-diagram-project')
            ->add(label: $this->project->name, url: backend_route('pm.project.detail', ['id' => $this->project->id]), icon: 'fa-solid fa-folder-open')
            ->add(label: __('pm::proposal.page_title'), icon: 'fa-solid fa-file-powerpoint');
    }

    private function proposalType(int $type): ProposalType
    {
        return ProposalType::tryFrom($type) ?? abort(400);
    }

    /** True for files uploaded by this form for this project (no traversal, no other folders). */
    private function isOwnMockup(string $path): bool
    {
        $folder = 'projects/'.$this->project->id.'/proposal/mockups/';

        return str_starts_with($path, $folder)
            && ! str_contains($path, '..')
            && ! str_contains(substr($path, strlen($folder)), '/');
    }

    private function loadProposal(ProjectProposal $proposal): void
    {
        $this->proposalId = $proposal->id;
        $this->type = $proposal->type->value;
        $this->title = $proposal->title;
        $this->subject = $proposal->subject;
        $this->proposalDate = $proposal->proposal_date->toDateString();
        $this->taxNote = $proposal->tax_note;
        $this->maintenanceMonths = $proposal->maintenance_months;
        $this->scope = $proposal->scope ?? ['goal' => '', 'menu' => [], 'mockups' => []];
        $this->features = $proposal->features ?? [];
        $timeline = $proposal->timeline ?? [];
        $this->timeline = [
            'start_date' => (string) ($timeline['start_date'] ?? ''),
            'extra_holidays' => $timeline['extra_holidays'] ?? [],
            'worked_holidays' => $timeline['worked_holidays'] ?? [],
            'tasks' => GanttScheduler::normalize($timeline['tasks'] ?? []),
        ];
        $this->loadSections($proposal->costing ?? [], $proposal->hosting ?? [], $proposal->deliverables ?? [], $proposal->after_sales ?? []);
        unset($this->proposal);
    }

    private function persist(): ProjectProposal
    {
        $proposal = $this->proposal;
        abort_if($proposal === null, 404);

        $this->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'proposalDate' => 'required|date',
            'maintenanceMonths' => 'required|integer|min:0|max:60',
            'timeline.start_date' => 'nullable|date',
            'timeline.tasks.*.name' => 'nullable|string|max:255',
            'timeline.tasks.*.duration' => 'required|integer|min:1|max:1000',
            'timeline.extra_holidays.*' => 'date',
        ]);

        $sections = $this->sectionsForStorage();
        $website = $proposal->type === ProposalType::Website;
        $proposal->fill([
            'title' => trim($this->title),
            'subject' => trim($this->subject),
            'proposal_date' => $this->proposalDate,
            'tax_note' => $this->taxNote,
            'maintenance_months' => $this->maintenanceMonths,
            'scope' => $website ? [
                'goal' => trim((string) ($this->scope['goal'] ?? '')),
                'menu' => $this->cleanTree($this->scope['menu'] ?? []),
                'mockups' => array_values(array_filter(
                    $this->scope['mockups'] ?? [],
                    fn (mixed $mockup): bool => is_array($mockup) && is_string($mockup['path'] ?? null) && $this->isOwnMockup($mockup['path']),
                )),
            ] : null,
            'features' => $website ? $this->cleanChecklist($this->features) : $this->cleanTree($this->features),
            'costing' => $sections['costing'],
            'hosting' => $sections['hosting'],
            'deliverables' => $sections['deliverables'],
            'after_sales' => $sections['after_sales'],
            'timeline' => [
                'start_date' => $this->timeline['start_date'] !== '' ? $this->timeline['start_date'] : null,
                'extra_holidays' => array_values(array_unique($this->timeline['extra_holidays'] ?? [])),
                'worked_holidays' => array_values(array_unique($this->timeline['worked_holidays'] ?? [])),
                'tasks' => $sections['timeline_tasks'],
            ],
        ])->save();

        unset($this->proposal, $this->grandTotal, $this->scheduledTasks);

        return $proposal;
    }

    /**
     * Modules / menu: drop rows and children without a name.
     *
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private function cleanTree(array $rows): array
    {
        $clean = [];
        foreach ($rows as $row) {
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $children = [];
            foreach ($row['children'] ?? [] as $child) {
                $childName = trim((string) ($child['name'] ?? ''));
                if ($childName !== '') {
                    $children[] = ['id' => (string) ($child['id'] ?? ProposalFactory::newRowId()), 'name' => $childName, 'description' => trim((string) ($child['description'] ?? ''))];
                }
            }
            $clean[] = ['id' => (string) ($row['id'] ?? ProposalFactory::newRowId()), 'name' => $name, 'description' => trim((string) ($row['description'] ?? '')), 'children' => $children];
        }

        return $clean;
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @return list<array<string, mixed>>
     */
    private function cleanChecklist(array $rows): array
    {
        return array_values(array_map(fn (array $row, int $i): array => [
            'id' => (string) ($row['id'] ?? ProposalFactory::newRowId()),
            'item_id' => $row['item_id'] ?? null,
            'name' => trim((string) ($row['name'] ?? '')),
            'description' => trim((string) ($row['description'] ?? '')),
            'checked' => (bool) ($row['checked'] ?? false),
            'sort' => (int) ($row['sort'] ?? ($i + 1) * 10),
        ], $rows, array_keys($rows)));
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
