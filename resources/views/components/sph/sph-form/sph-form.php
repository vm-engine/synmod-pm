<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use VmEngine\Pm\Form\Concerns\EditsRowsAndTimeline;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\IssuerService;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;
use VmEngine\Pm\Services\Proposal\ProposalFactory;
use VmEngine\Pm\Services\SignatureService;
use VmEngine\Pm\Services\Sph\SphDocumentData;
use VmEngine\Pm\Services\Sph\SphFactory;
use VmEngine\Pm\Services\Sph\SphPublisher;
use VmEngine\Pm\Services\Sph\SphValidator;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    use EditsRowsAndTimeline;
    use WithFileUploads;

    /** @var array<string, list<string>> */
    public array $tabErrors = [];

    public Project $project;

    #[Locked]
    public int $sphId = 0;

    public string $sphDate = '';

    public string $subject = '';

    public string $attachmentLabel = '';

    public string $recipientName = '';

    public string $recipientTitle = '';

    public string $recipientCompany = '';

    public string $recipientAddress = '';

    public string $need = '';

    public ?int $issuerId = null;

    public ?int $signatureId = null;

    public bool $hasSignature = true;

    /** @var list<array{id: string, name: string, description: string, duration: string, price: float|string}> */
    public array $items = [];

    public bool $showDuration = false;

    public bool $taxNote = true;

    public bool $paymentEnabled = false;

    public float|string $dpPercentage = 30;

    public bool $showTimelineSummary = false;

    public bool $showTimeplan = false;

    /** @var array{start_date: string, extra_holidays: list<string>, worked_holidays: list<string>, tasks: list<array<string, mixed>>} */
    public array $timeline = ['start_date' => '', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []];

    /** @var list<array{path: string, title: string}> */
    public array $attachments = [];

    /** @var array<int, TemporaryUploadedFile> */
    public array $attachmentUploads = [];

    public function mount(int $id): void
    {
        $this->project = Project::with('client', 'proposal', 'sph')->findOrFail($id);
        $this->loadSph($this->project->sph ?? app(SphFactory::class)->create($this->project));

        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return __('pm::sph.page_title');
    }

    #[Computed]
    public function sph(): ProjectSph
    {
        return $this->project->sph()->findOrFail($this->sphId);
    }

    #[Computed]
    public function grandTotal(): float
    {
        return ProjectSph::computeGrandTotal(array_map(
            fn (array $row): array => ['price' => self::parseAmount($row['price'] ?? 0)],
            $this->items,
        ));
    }

    /** "DP Rp6.000.000,- (30%) · remaining 70%" helper values for the Terms tab. */
    #[Computed]
    public function paymentPreview(): string
    {
        $dp = (float) self::parseAmount($this->dpPercentage);

        return __('pm::sph.dp_preview', [
            'dp' => ProposalDocumentData::money(round($this->grandTotal * $dp / 100)).' ('.SphDocumentData::percent($dp).'%)',
            'rest' => SphDocumentData::percent(100 - $dp).'%',
        ]);
    }

    #[Computed]
    public function workingDays(): int
    {
        return GanttLayout::build($this->scheduledTasks, $this->workCalendar())->workingDays;
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    #[Computed]
    public function issuers(): array
    {
        return app(IssuerService::class)->getOptions();
    }

    /**
     * @return array<int, array{value: int, label: string}>
     */
    #[Computed]
    public function signatures(): array
    {
        return app(SignatureService::class)->getOptions();
    }

    public function save(): void
    {
        $this->persist();
        $this->dispatch('notify', variant: 'success', title: __('pm::sph.save'), message: __('pm::sph.saved'));
    }

    /**
     * Save, validate and return the preview URL (null when invalid).
     * The browser opens it itself: a window.open() after this round-trip would be popup-blocked.
     */
    public function preview(string $format): ?string
    {
        abort_unless(in_array($format, ['pdf', 'docx'], true), 400);
        $this->tabErrors = app(SphValidator::class)->errors($this->persist());
        if ($this->tabErrors !== []) {
            $title = $format === 'pdf' ? __('pm::sph.preview_pdf') : __('pm::sph.preview_docx');
            $this->dispatch('notify', variant: 'danger', title: $title, message: __('pm::sph.validation_failed'));

            return null;
        }

        return backend_route('pm.sph.preview', ['id' => $this->project->id, 'format' => $format]);
    }

    public function publish(): void
    {
        $sph = $this->persist();
        try {
            app(SphPublisher::class)->publish($sph, (int) Auth::id());
        } catch (ProposalValidationException $e) {
            $this->tabErrors = $e->errors;
            $this->dispatch('notify', variant: 'danger', title: __('pm::sph.publish'), message: __('pm::sph.validation_failed'));

            return;
        }
        $this->tabErrors = [];
        unset($this->sph);
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::sph.publish'), message: __('pm::sph.published'));
    }

    public function importFromProposal(): void
    {
        abort_if($this->project->proposal === null, 404);
        $this->loadSph(app(SphFactory::class)->importFromProposal($this->persist()));
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::sph.import_from_proposal'), message: __('pm::sph.imported'));
    }

    public function updatedAttachmentUploads(): void
    {
        $this->validate(['attachmentUploads.*' => 'image|mimes:jpg,jpeg,png|max:5120']);
        foreach ($this->attachmentUploads as $upload) {
            $this->attachments[] = ['path' => (string) $upload->store($this->attachmentFolder(), 'local'), 'title' => ''];
        }
        $this->attachmentUploads = [];
    }

    public function removeAttachment(int $index): void
    {
        // The file itself is deleted by persist(), once the removal is saved
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    #[Computed]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(label: __('pm::menu.be.parent'), icon: 'fa-solid fa-list')
            ->add(label: __('pm::project.list_title'), url: backend_route('pm.project.index'), icon: 'fa-solid fa-diagram-project')
            ->add(label: $this->project->name, url: backend_route('pm.project.detail', ['id' => $this->project->id]), icon: 'fa-solid fa-folder-open')
            ->add(label: __('pm::sph.page_title'), icon: 'fa-solid fa-file-invoice');
    }

    /**
     * @return array<string, mixed>
     */
    protected function blankRow(string $list): array
    {
        return match ($list) {
            'items' => ['name' => '', 'description' => '', 'duration' => '', 'price' => 0],
            'timeline.tasks' => $this->blankTaskRow(),
            default => abort(400),
        };
    }

    private function attachmentFolder(): string
    {
        return 'projects/'.$this->project->id.'/sph/attachments';
    }

    /** True for files uploaded by this form for this project (no traversal, no other folders). */
    private function isOwnAttachment(string $path): bool
    {
        $folder = $this->attachmentFolder().'/';

        return str_starts_with($path, $folder)
            && ! str_contains($path, '..')
            && ! str_contains(substr($path, strlen($folder)), '/');
    }

    private function loadSph(ProjectSph $sph): void
    {
        $this->sphId = $sph->id;
        $this->sphDate = $sph->sph_date->toDateString();
        $this->subject = $sph->subject;
        $this->attachmentLabel = $sph->attachment_label;
        $this->recipientName = $sph->recipient_name;
        $this->recipientTitle = $sph->recipient_title;
        $this->recipientCompany = $sph->recipient_company;
        $this->recipientAddress = (string) $sph->recipient_address;
        $this->need = $sph->need;
        $this->issuerId = $sph->issuer_id;
        $this->signatureId = $sph->signature_id;
        $this->hasSignature = $sph->has_signature;
        $this->items = array_map(fn (array $row): array => [
            'id' => (string) ($row['id'] ?? ProposalFactory::newRowId()),
            'name' => (string) ($row['name'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'duration' => (string) ($row['duration'] ?? ''),
            'price' => (float) ($row['price'] ?? 0),
        ], array_values($sph->items ?? []));
        $this->showDuration = $sph->show_duration;
        $this->taxNote = $sph->tax_note;
        $this->paymentEnabled = $sph->payment_enabled;
        $this->dpPercentage = (float) $sph->dp_percentage;
        $this->showTimelineSummary = $sph->show_timeline_summary;
        $this->showTimeplan = $sph->show_timeplan;
        $timeline = $sph->timeline ?? [];
        $this->timeline = [
            'start_date' => (string) ($timeline['start_date'] ?? ''),
            'extra_holidays' => $timeline['extra_holidays'] ?? [],
            'worked_holidays' => $timeline['worked_holidays'] ?? [],
            'tasks' => GanttScheduler::normalize($timeline['tasks'] ?? []),
        ];
        $this->attachments = array_values($sph->attachments ?? []);
        unset($this->sph, $this->grandTotal);
        $this->forgetSchedule();
    }

    private function persist(): ProjectSph
    {
        $this->validate([
            'sphDate' => 'required|date',
            'subject' => 'required|string|max:255',
            'attachmentLabel' => 'nullable|string|max:100',
            'recipientName' => 'nullable|string|max:255',
            'recipientTitle' => 'nullable|string|max:255',
            'recipientCompany' => 'nullable|string|max:255',
            'recipientAddress' => 'nullable|string|max:1000',
            'need' => 'nullable|string|max:500',
            'issuerId' => 'nullable|integer|exists:document_issuers,id',
            'signatureId' => 'nullable|integer|exists:signatures,id',
            'items.*.name' => 'nullable|string|max:255',
            'items.*.description' => 'nullable|string|max:5000',
            'items.*.duration' => 'nullable|string|max:100',
            'attachments.*.title' => 'nullable|string|max:255',
            'timeline.start_date' => 'nullable|date',
            'timeline.tasks.*.name' => 'nullable|string|max:255',
            'timeline.tasks.*.duration' => 'required|integer|min:1|max:1000',
            'timeline.extra_holidays.*' => 'date',
        ]);

        $sph = $this->sph;
        $previousPaths = array_column($sph->attachments ?? [], 'path');
        $sph->fill([
            'sph_date' => CarbonImmutable::parse($this->sphDate)->toDateString(),
            'subject' => trim($this->subject),
            'attachment_label' => trim($this->attachmentLabel),
            'recipient_name' => trim($this->recipientName),
            'recipient_title' => trim($this->recipientTitle),
            'recipient_company' => trim($this->recipientCompany),
            'recipient_address' => trim($this->recipientAddress),
            'need' => trim($this->need),
            'issuer_id' => $this->issuerId,
            'signature_id' => $this->signatureId,
            'has_signature' => $this->hasSignature,
            'items' => array_map(fn (array $row): array => [
                'id' => (string) $row['id'],
                'name' => trim((string) ($row['name'] ?? '')),
                'description' => rtrim((string) ($row['description'] ?? '')),
                'duration' => trim((string) ($row['duration'] ?? '')),
                'price' => self::parseAmount($row['price'] ?? 0),
            ], array_values($this->items)),
            'show_duration' => $this->showDuration,
            'tax_note' => $this->taxNote,
            'payment_enabled' => $this->paymentEnabled,
            'dp_percentage' => min(100.0, max(0.0, self::parseAmount($this->dpPercentage))),
            'timeline' => [
                'start_date' => $this->timeline['start_date'] !== '' ? $this->timeline['start_date'] : null,
                'extra_holidays' => array_values(array_unique($this->timeline['extra_holidays'] ?? [])),
                'worked_holidays' => array_values(array_unique($this->timeline['worked_holidays'] ?? [])),
                'tasks' => GanttScheduler::normalize($this->timeline['tasks'] ?? []),
            ],
            'show_timeline_summary' => $this->showTimelineSummary,
            'show_timeplan' => $this->showTimeplan,
            'attachments' => array_values(array_map(
                fn (array $attachment): array => ['path' => $attachment['path'], 'title' => trim((string) ($attachment['title'] ?? ''))],
                array_filter(
                    $this->attachments,
                    fn (mixed $attachment): bool => is_array($attachment) && is_string($attachment['path'] ?? null) && $this->isOwnAttachment($attachment['path']),
                ),
            )),
        ])->save();

        // Files of removed attachments; paths come from the DB row, so they are our own uploads
        Storage::disk('local')->delete(array_values(array_diff($previousPaths, array_column($sph->attachments, 'path'))));

        unset($this->sph, $this->grandTotal);
        $this->forgetSchedule();

        return $sph;
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
