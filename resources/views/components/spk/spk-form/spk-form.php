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
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\BankAccountService;
use VmEngine\Pm\Services\IssuerService;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Proposal\ProposalFactory;
use VmEngine\Pm\Services\SignatureService;
use VmEngine\Pm\Services\Spk\SpkDocumentData;
use VmEngine\Pm\Services\Spk\SpkFactory;
use VmEngine\Pm\Services\Spk\SpkPublisher;
use VmEngine\Pm\Services\Spk\SpkValidator;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    use EditsRowsAndTimeline;
    use WithFileUploads;

    private const CONTACT_KEYS = ['name', 'address', 'phone', 'email'];

    /** @var array<string, list<string>> */
    public array $tabErrors = [];

    public Project $project;

    #[Locked]
    public int $spkId = 0;

    public string $spkDate = '';

    public string $title = '';

    public string $party1Company = '';

    public string $party1Address = '';

    public string $party1Representative = '';

    public string $party1Position = '';

    public string $party1Description = '';

    public ?int $issuerId = null;

    public ?int $signatureId = null;

    public bool $hasSignature = true;

    public string $party2Address = '';

    public string $party2Description = '';

    public string $asas = '';

    public string $scope = '';

    public string $referenceDocument = '';

    public int|string $workDuration = 0;

    public string $workDurationUnit = 'hari kerja';

    public string $workStartText = '';

    public int|string $maintenanceMonths = 0;

    public string $durationNotes = '';

    public string $obligationsFirst = '';

    public string $obligationsSecond = '';

    public float|string $contractValue = 0;

    public bool $taxNote = true;

    /** @var list<array{id: string, label: string, type: string, value: float|string, count: int|string, due: string}> */
    public array $installments = [];

    public string $paymentNotes = '';

    public ?int $bankAccountId = null;

    public bool $serverEnabled = false;

    public string $serverText = '';

    public string $confidentiality = '';

    public string $dispute = '';

    public bool $correspondenceEnabled = true;

    /** @var array{first: array<string, string>, second: array<string, string>} */
    public array $contacts = ['first' => [], 'second' => []];

    public string $correspondenceText = '';

    /** @var list<array{id: string, title: string, body: string}> */
    public array $extraPasal = [];

    public string $misc = '';

    /** Unused: the SPK has no timeline, but EditsRowsAndTimeline expects the property. */
    public array $timeline = ['start_date' => '', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []];

    /** @var list<array{path: string, title: string}> */
    public array $attachments = [];

    /** @var array<int, TemporaryUploadedFile> */
    public array $attachmentUploads = [];

    public function mount(int $id): void
    {
        $this->project = Project::with('client', 'proposal', 'sph', 'spk')->findOrFail($id);
        $this->loadSpk($this->project->spk ?? app(SpkFactory::class)->create($this->project));

        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return __('pm::spk.page_title');
    }

    #[Computed]
    public function spk(): ProjectSpk
    {
        return $this->project->spk()->findOrFail($this->spkId);
    }

    /** Installments total (for the sum check under the payment scheme). */
    #[Computed]
    public function grandTotal(): float
    {
        return ProjectSpk::installmentsTotal($this->normalizedInstallments(), $this->contractValueAmount);
    }

    #[Computed]
    public function contractValueAmount(): float
    {
        return self::parseAmount($this->contractValue);
    }

    /**
     * Pasal numbers for the current toggles (they shift when optional pasal are switched).
     *
     * @return array<string, int>
     */
    #[Computed]
    public function pasalNumbers(): array
    {
        return SpkDocumentData::pasalNumbers($this->serverEnabled, $this->correspondenceEnabled, count($this->extraPasal));
    }

    /**
     * @return array<string, string> tab key => label
     */
    #[Computed]
    public function tabs(): array
    {
        $n = $this->pasalNumbers;
        $list = fn (string ...$keys): string => implode(', ', array_map(fn (string $key): int => $n[$key], array_values(array_filter($keys, fn (string $key): bool => isset($n[$key])))));
        $extra = array_values(array_filter(array_keys($n), fn (string $key): bool => str_starts_with($key, 'extra.')));

        return [
            'general' => __('pm::spk.tab_general'),
            'scope' => __('pm::spk.tab_scope', ['pasal' => $list('asas', 'pokok')]),
            'duration' => __('pm::spk.tab_duration', ['pasal' => $list('duration', 'obligations')]),
            'payment' => __('pm::spk.tab_payment', ['pasal' => $list('payment', 'server')]),
            'terms' => __('pm::spk.tab_terms', ['pasal' => $list('confidentiality', 'dispute', 'correspondence', 'misc')]),
            'extra' => $extra === [] ? __('pm::spk.tab_extra') : __('pm::spk.tab_extra_numbered', ['pasal' => $list(...$extra)]),
            'attachments' => __('pm::spk.tab_attachments'),
        ];
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

    /**
     * @return array<int, array{value: int, label: string}>
     */
    #[Computed]
    public function bankAccounts(): array
    {
        return app(BankAccountService::class)->getOptions();
    }

    public function updated(string $property): void
    {
        // Labels and totals depend on these
        if (in_array($property, ['serverEnabled', 'correspondenceEnabled'], true)) {
            unset($this->pasalNumbers, $this->tabs);
        }
        if ($property === 'contractValue' || str_starts_with($property, 'installments')) {
            unset($this->grandTotal, $this->contractValueAmount);
        }
    }

    public function save(): void
    {
        $this->persist();
        $this->dispatch('notify', variant: 'success', title: __('pm::spk.save'), message: __('pm::spk.saved'));
    }

    /**
     * Save, validate and return the preview URL (null when invalid).
     * The browser opens it itself: a window.open() after this round-trip would be popup-blocked.
     */
    public function preview(string $format): ?string
    {
        abort_unless(in_array($format, ['pdf', 'docx'], true), 400);
        $this->tabErrors = app(SpkValidator::class)->errors($this->persist());
        if ($this->tabErrors !== []) {
            $title = $format === 'pdf' ? __('pm::spk.preview_pdf') : __('pm::spk.preview_docx');
            $this->dispatch('notify', variant: 'danger', title: $title, message: __('pm::spk.validation_failed'));

            return null;
        }

        return backend_route('pm.spk.preview', ['id' => $this->project->id, 'format' => $format]);
    }

    public function publish(): void
    {
        $spk = $this->persist();
        try {
            app(SpkPublisher::class)->publish($spk, (int) Auth::id());
        } catch (ProposalValidationException $e) {
            $this->tabErrors = $e->errors;
            $this->dispatch('notify', variant: 'danger', title: __('pm::spk.publish'), message: __('pm::spk.validation_failed'));

            return;
        }
        $this->tabErrors = [];
        unset($this->spk);
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::spk.publish'), message: __('pm::spk.published'));
    }

    public function importFromSph(): void
    {
        abort_if($this->project->sph === null, 404);
        $this->loadSpk(app(SpkFactory::class)->importFromSph($this->persist()));
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::spk.import_from_sph'), message: __('pm::spk.imported'));
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
            ->add(label: __('pm::spk.page_title'), icon: 'fa-solid fa-file-signature');
    }

    /**
     * @return array<string, mixed>
     */
    protected function blankRow(string $list): array
    {
        unset($this->pasalNumbers, $this->tabs);

        return match ($list) {
            'installments' => ['label' => '', 'type' => 'percent', 'value' => 0, 'count' => 1, 'due' => ''],
            'extraPasal' => ['title' => '', 'body' => ''],
            default => abort(400),
        };
    }

    private function attachmentFolder(): string
    {
        return 'projects/'.$this->project->id.'/spk/attachments';
    }

    /** True for files uploaded by this form for this project (no traversal, no other folders). */
    private function isOwnAttachment(string $path): bool
    {
        $folder = $this->attachmentFolder().'/';

        return str_starts_with($path, $folder)
            && ! str_contains($path, '..')
            && ! str_contains(substr($path, strlen($folder)), '/');
    }

    /**
     * @return list<array{id: string, label: string, type: string, value: float, count: int, due: string}>
     */
    private function normalizedInstallments(): array
    {
        return array_values(array_map(fn (array $row): array => [
            'id' => (string) $row['id'],
            'label' => trim((string) ($row['label'] ?? '')),
            'type' => ($row['type'] ?? 'percent') === 'amount' ? 'amount' : 'percent',
            'value' => self::parseAmount($row['value'] ?? 0),
            'count' => max(1, (int) ($row['count'] ?? 1)),
            'due' => trim((string) ($row['due'] ?? '')),
        ], $this->installments));
    }

    /**
     * @param  array<string, mixed>  $contact
     * @return array<string, string>
     */
    private static function contact(array $contact): array
    {
        return array_combine(self::CONTACT_KEYS, array_map(fn (string $key): string => trim((string) ($contact[$key] ?? '')), self::CONTACT_KEYS));
    }

    private function loadSpk(ProjectSpk $spk): void
    {
        $this->spkId = $spk->id;
        $this->spkDate = $spk->spk_date->toDateString();
        $this->title = $spk->title;
        $this->party1Company = $spk->party1_company;
        $this->party1Address = (string) $spk->party1_address;
        $this->party1Representative = $spk->party1_representative;
        $this->party1Position = $spk->party1_position;
        $this->party1Description = $spk->party1_description;
        $this->issuerId = $spk->issuer_id;
        $this->signatureId = $spk->signature_id;
        $this->hasSignature = $spk->has_signature;
        $this->party2Address = (string) $spk->party2_address;
        $this->party2Description = $spk->party2_description;
        $this->asas = $spk->asas;
        $this->scope = $spk->scope;
        $this->referenceDocument = $spk->reference_document;
        $this->workDuration = $spk->work_duration;
        $this->workDurationUnit = $spk->work_duration_unit;
        $this->workStartText = $spk->work_start_text;
        $this->maintenanceMonths = $spk->maintenance_months;
        $this->durationNotes = (string) $spk->duration_notes;
        $this->obligationsFirst = $spk->obligations_first;
        $this->obligationsSecond = $spk->obligations_second;
        $this->contractValue = (float) $spk->contract_value;
        $this->taxNote = $spk->tax_note;
        $this->installments = array_map(fn (array $row): array => [
            'id' => (string) ($row['id'] ?? ProposalFactory::newRowId()),
            'label' => (string) ($row['label'] ?? ''),
            'type' => (string) ($row['type'] ?? 'percent'),
            'value' => (float) ($row['value'] ?? 0),
            'count' => (int) ($row['count'] ?? 1),
            'due' => (string) ($row['due'] ?? ''),
        ], array_values($spk->installments ?? []));
        $this->paymentNotes = (string) $spk->payment_notes;
        $this->bankAccountId = $spk->bank_account_id;
        $this->serverEnabled = $spk->server_enabled;
        $this->serverText = (string) $spk->server_text;
        $this->confidentiality = $spk->confidentiality;
        $this->dispute = $spk->dispute;
        $this->correspondenceEnabled = $spk->correspondence_enabled;
        $this->contacts = ['first' => self::contact($spk->contacts['first'] ?? []), 'second' => self::contact($spk->contacts['second'] ?? [])];
        $this->correspondenceText = (string) $spk->correspondence_text;
        $this->extraPasal = array_map(fn (array $row): array => [
            'id' => (string) ($row['id'] ?? ProposalFactory::newRowId()),
            'title' => (string) ($row['title'] ?? ''),
            'body' => (string) ($row['body'] ?? ''),
        ], array_values($spk->extra_pasal ?? []));
        $this->misc = $spk->misc;
        $this->attachments = array_values($spk->attachments ?? []);
        unset($this->spk, $this->grandTotal, $this->contractValueAmount, $this->pasalNumbers, $this->tabs);
    }

    private function persist(): ProjectSpk
    {
        $text = 'nullable|string|max:20000';
        $this->validate([
            'spkDate' => 'required|date',
            'title' => 'required|string|max:500',
            'party1Company' => 'nullable|string|max:255',
            'party1Address' => 'nullable|string|max:1000',
            'party1Representative' => 'nullable|string|max:255',
            'party1Position' => 'nullable|string|max:255',
            'party1Description' => 'nullable|string|max:500',
            'issuerId' => 'nullable|integer|exists:document_issuers,id',
            'signatureId' => 'nullable|integer|exists:signatures,id',
            'party2Address' => 'nullable|string|max:1000',
            'party2Description' => 'nullable|string|max:500',
            'asas' => $text,
            'scope' => $text,
            'referenceDocument' => 'nullable|string|max:500',
            'workDuration' => 'required|integer|min:0|max:9999',
            'workDurationUnit' => 'required|in:hari kerja,hari kalender,bulan',
            'workStartText' => 'nullable|string|max:500',
            'maintenanceMonths' => 'required|integer|min:0|max:120',
            'durationNotes' => $text,
            'obligationsFirst' => $text,
            'obligationsSecond' => $text,
            'installments.*.label' => 'nullable|string|max:255',
            'installments.*.type' => 'required|in:percent,amount',
            'installments.*.count' => 'required|integer|min:1|max:120',
            'installments.*.due' => 'nullable|string|max:500',
            'paymentNotes' => $text,
            'bankAccountId' => 'nullable|integer|exists:bank_accounts,id',
            'serverText' => $text,
            'confidentiality' => $text,
            'dispute' => $text,
            'contacts.*.name' => 'nullable|string|max:255',
            'contacts.*.address' => 'nullable|string|max:500',
            'contacts.*.phone' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|string|max:255',
            'correspondenceText' => $text,
            'extraPasal.*.title' => 'nullable|string|max:255',
            'extraPasal.*.body' => $text,
            'misc' => $text,
            'attachments.*.title' => 'nullable|string|max:255',
        ]);

        $spk = $this->spk;
        $previousPaths = array_column($spk->attachments ?? [], 'path');
        $spk->fill([
            'spk_date' => CarbonImmutable::parse($this->spkDate)->toDateString(),
            'title' => trim($this->title),
            'party1_company' => trim($this->party1Company),
            'party1_address' => trim($this->party1Address),
            'party1_representative' => trim($this->party1Representative),
            'party1_position' => trim($this->party1Position),
            'party1_description' => trim($this->party1Description),
            'issuer_id' => $this->issuerId,
            'signature_id' => $this->signatureId,
            'has_signature' => $this->hasSignature,
            'party2_address' => trim($this->party2Address),
            'party2_description' => trim($this->party2Description),
            'asas' => rtrim($this->asas),
            'scope' => rtrim($this->scope),
            'reference_document' => trim($this->referenceDocument),
            'work_duration' => (int) $this->workDuration,
            'work_duration_unit' => $this->workDurationUnit,
            'work_start_text' => trim($this->workStartText),
            'maintenance_months' => (int) $this->maintenanceMonths,
            'duration_notes' => rtrim($this->durationNotes),
            'obligations_first' => rtrim($this->obligationsFirst),
            'obligations_second' => rtrim($this->obligationsSecond),
            'contract_value' => max(0.0, self::parseAmount($this->contractValue)),
            'tax_note' => $this->taxNote,
            'installments' => $this->normalizedInstallments(),
            'payment_notes' => rtrim($this->paymentNotes),
            'bank_account_id' => $this->bankAccountId,
            'server_enabled' => $this->serverEnabled,
            'server_text' => rtrim($this->serverText),
            'confidentiality' => rtrim($this->confidentiality),
            'dispute' => rtrim($this->dispute),
            'correspondence_enabled' => $this->correspondenceEnabled,
            'contacts' => ['first' => self::contact($this->contacts['first'] ?? []), 'second' => self::contact($this->contacts['second'] ?? [])],
            'correspondence_text' => rtrim($this->correspondenceText),
            'extra_pasal' => array_values(array_map(fn (array $row): array => [
                'id' => (string) $row['id'],
                'title' => trim((string) ($row['title'] ?? '')),
                'body' => rtrim((string) ($row['body'] ?? '')),
            ], $this->extraPasal)),
            'misc' => rtrim($this->misc),
            'attachments' => array_values(array_map(
                fn (array $attachment): array => ['path' => $attachment['path'], 'title' => trim((string) ($attachment['title'] ?? ''))],
                array_filter(
                    $this->attachments,
                    fn (mixed $attachment): bool => is_array($attachment) && is_string($attachment['path'] ?? null) && $this->isOwnAttachment($attachment['path']),
                ),
            )),
        ])->save();

        // Files of removed attachments; paths come from the DB row, so they are our own uploads
        Storage::disk('local')->delete(array_values(array_diff($previousPaths, array_column($spk->attachments, 'path'))));

        unset($this->spk, $this->grandTotal);

        return $spk;
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
