<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Factories\ProjectSphFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * Surat Penawaran Harga generated for a project (one per project, versioned on publish).
 *
 * @property int $id
 * @property int $project_id
 * @property ProposalStatus $status
 * @property string|null $number
 * @property Carbon $sph_date
 * @property string $subject
 * @property string $attachment_label
 * @property string $recipient_name
 * @property string $recipient_title
 * @property string $recipient_company
 * @property string|null $recipient_address
 * @property string $need
 * @property int|null $issuer_id
 * @property int|null $signature_id
 * @property bool $has_signature
 * @property list<array<string, mixed>> $items rows {id, name, description (Markdown), duration, price}
 * @property bool $show_duration
 * @property bool $tax_note
 * @property bool $payment_enabled
 * @property string $dp_percentage
 * @property array{start_date?: string|null, extra_holidays?: list<string>, worked_holidays?: list<string>, tasks?: list<array<string, mixed>>} $timeline
 * @property bool $show_timeline_summary
 * @property bool $show_timeplan
 * @property list<array<string, mixed>> $attachments rows {path, title}
 * @property string $grand_total
 * @property array<string, mixed>|null $published_snapshot
 * @property int|null $published_version
 * @property Carbon|null $published_at
 * @property int|null $published_by
 * @property bool $has_unpublished_changes
 * @property-read Project $project
 * @property-read DocumentIssuer|null $issuer
 * @property-read Signature|null $signature
 */
class ProjectSph extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectSphFactory> */
    use HasFactory;

    use HasUpdater;

    /** Columns whose change makes a published SPH "unpublished changes". */
    public const CONTENT_FIELDS = [
        'sph_date', 'subject', 'attachment_label', 'recipient_name', 'recipient_title', 'recipient_company',
        'recipient_address', 'need', 'issuer_id', 'signature_id', 'has_signature', 'items', 'show_duration',
        'tax_note', 'payment_enabled', 'dp_percentage', 'timeline', 'show_timeline_summary', 'show_timeplan', 'attachments',
    ];

    protected $fillable = [
        'project_id', 'status', 'number', ...self::CONTENT_FIELDS, 'grand_total', 'published_snapshot',
        'published_version', 'published_at', 'published_by', 'has_unpublished_changes', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProposalStatus::class,
            'sph_date' => 'date',
            'has_signature' => 'boolean',
            'items' => 'array',
            'show_duration' => 'boolean',
            'tax_note' => 'boolean',
            'payment_enabled' => 'boolean',
            'dp_percentage' => 'decimal:2',
            'timeline' => 'array',
            'show_timeline_summary' => 'boolean',
            'show_timeplan' => 'boolean',
            'attachments' => 'array',
            'grand_total' => 'decimal:2',
            'published_snapshot' => 'array',
            'published_version' => 'integer',
            'published_at' => 'datetime',
            'has_unpublished_changes' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectSphFactory
    {
        return ProjectSphFactory::new();
    }

    protected static function booted(): void
    {
        static::saving(function (self $sph): void {
            $sph->grand_total = (string) self::computeGrandTotal($sph->items ?? []);

            if ($sph->exists
                && $sph->getOriginal('status') === ProposalStatus::Published
                && ! $sph->isDirty('published_snapshot')
                && $sph->isDirty(self::CONTENT_FIELDS)) {
                $sph->has_unpublished_changes = true;
            }
        });
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    public static function computeGrandTotal(array $items): float
    {
        return round(array_sum(array_map(fn (array $row): float => (float) ($row['price'] ?? 0), $items)), 2);
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
}
