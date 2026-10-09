<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Factories\ProjectSpkFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * Surat Perjanjian Kerja Sama generated for a project (one per project, versioned on publish).
 *
 * @property int $id
 * @property int $project_id
 * @property ProposalStatus $status
 * @property string|null $number
 * @property Carbon $spk_date
 * @property string $title
 * @property string $party1_company
 * @property string|null $party1_address
 * @property string $party1_representative
 * @property string $party1_position
 * @property string $party1_description
 * @property int|null $issuer_id
 * @property int|null $signature_id
 * @property bool $has_signature
 * @property string|null $party2_address
 * @property string $party2_description
 * @property string $asas
 * @property string $scope
 * @property string $reference_document
 * @property int $work_duration
 * @property string $work_duration_unit
 * @property string $work_start_text
 * @property int $maintenance_months
 * @property string|null $duration_notes
 * @property string $obligations_first
 * @property string $obligations_second
 * @property string $contract_value
 * @property bool $tax_note
 * @property list<array<string, mixed>> $installments rows {id, label, type (percent|amount), value, count, due}
 * @property string|null $payment_notes
 * @property int|null $bank_account_id
 * @property bool $server_enabled
 * @property string|null $server_text
 * @property string $confidentiality
 * @property string $dispute
 * @property bool $correspondence_enabled
 * @property array{first?: array<string, string>, second?: array<string, string>} $contacts
 * @property string|null $correspondence_text
 * @property list<array<string, mixed>> $extra_pasal rows {id, title, body (Markdown)}
 * @property string $misc
 * @property list<array<string, mixed>> $attachments rows {path, title}
 * @property array<string, mixed>|null $published_snapshot
 * @property int|null $published_version
 * @property Carbon|null $published_at
 * @property int|null $published_by
 * @property bool $has_unpublished_changes
 * @property-read Project $project
 * @property-read DocumentIssuer|null $issuer
 * @property-read Signature|null $signature
 * @property-read BankAccount|null $bankAccount
 */
class ProjectSpk extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectSpkFactory> */
    use HasFactory;

    use HasUpdater;

    /** Columns whose change makes a published SPK "unpublished changes". */
    public const CONTENT_FIELDS = [
        'spk_date', 'title', 'party1_company', 'party1_address', 'party1_representative', 'party1_position',
        'party1_description', 'issuer_id', 'signature_id', 'has_signature', 'party2_address', 'party2_description',
        'asas', 'scope', 'reference_document', 'work_duration', 'work_duration_unit', 'work_start_text',
        'maintenance_months', 'duration_notes', 'obligations_first', 'obligations_second', 'contract_value',
        'tax_note', 'installments', 'payment_notes', 'bank_account_id', 'server_enabled', 'server_text',
        'confidentiality', 'dispute', 'correspondence_enabled', 'contacts', 'correspondence_text', 'extra_pasal',
        'misc', 'attachments',
    ];

    protected $fillable = [
        'project_id', 'status', 'number', ...self::CONTENT_FIELDS, 'published_snapshot', 'published_version',
        'published_at', 'published_by', 'has_unpublished_changes', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProposalStatus::class,
            'spk_date' => 'date',
            'has_signature' => 'boolean',
            'work_duration' => 'integer',
            'maintenance_months' => 'integer',
            'contract_value' => 'decimal:2',
            'tax_note' => 'boolean',
            'installments' => 'array',
            'server_enabled' => 'boolean',
            'correspondence_enabled' => 'boolean',
            'contacts' => 'array',
            'extra_pasal' => 'array',
            'attachments' => 'array',
            'published_snapshot' => 'array',
            'published_version' => 'integer',
            'published_at' => 'datetime',
            'has_unpublished_changes' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectSpkFactory
    {
        return ProjectSpkFactory::new();
    }

    protected static function booted(): void
    {
        static::saving(function (self $spk): void {
            if ($spk->exists
                && $spk->getOriginal('status') === ProposalStatus::Published
                && ! $spk->isDirty('published_snapshot')
                && $spk->isDirty(self::CONTENT_FIELDS)) {
                $spk->has_unpublished_changes = true;
            }
        });
    }

    /**
     * Rupiah amount of one installment: percent rows are a share of the contract value (whole rupiah).
     *
     * @param  array<string, mixed>  $row
     */
    public static function installmentAmount(array $row, float $contractValue): float
    {
        $value = (float) ($row['value'] ?? 0);

        return ($row['type'] ?? 'percent') === 'percent' ? round($contractValue * $value / 100) : round($value, 2);
    }

    /**
     * Sum of all installments, each times its count.
     *
     * @param  list<array<string, mixed>>  $rows
     */
    public static function installmentsTotal(array $rows, float $contractValue): float
    {
        return round(array_sum(array_map(
            fn (array $row): float => self::installmentAmount($row, $contractValue) * max(1, (int) ($row['count'] ?? 1)),
            $rows,
        )), 2);
    }

    public function isPublished(): bool
    {
        return $this->status === ProposalStatus::Published;
    }

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<DocumentIssuer, $this>
     */
    public function issuer(): BelongsTo
    {
        return $this->belongsTo(DocumentIssuer::class);
    }

    /**
     * @return BelongsTo<Signature, $this>
     */
    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    /**
     * @return BelongsTo<BankAccount, $this>
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
