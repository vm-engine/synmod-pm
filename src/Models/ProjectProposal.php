<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Factories\ProjectProposalFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property int $project_id
 * @property ProposalType $type
 * @property ProposalStatus $status
 * @property string|null $number
 * @property string $title
 * @property string $subject
 * @property Carbon $proposal_date
 * @property bool $tax_note
 * @property int $maintenance_months
 * @property string $grand_total
 * @property array<string, mixed>|null $scope
 * @property list<array<string, mixed>> $features
 * @property list<array<string, mixed>> $costing
 * @property list<array<string, mixed>> $hosting
 * @property array{start_date?: string|null, extra_holidays?: list<string>, worked_holidays?: list<string>, tasks?: list<array<string, mixed>>} $timeline
 * @property list<array<string, mixed>> $deliverables
 * @property list<array<string, mixed>> $after_sales
 * @property array<string, mixed>|null $published_snapshot
 * @property int|null $published_version
 * @property Carbon|null $published_at
 * @property int|null $published_by
 * @property bool $has_unpublished_changes
 * @property-read Project $project
 */
class ProjectProposal extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectProposalFactory> */
    use HasFactory;

    use HasUpdater;

    /** Columns whose change makes a published proposal "unpublished changes". */
    public const CONTENT_FIELDS = [
        'type', 'title', 'subject', 'proposal_date', 'tax_note', 'maintenance_months',
        'scope', 'features', 'costing', 'hosting', 'timeline', 'deliverables', 'after_sales',
    ];

    protected $fillable = [
        'project_id', 'type', 'status', 'number', 'title', 'subject', 'proposal_date', 'tax_note',
        'maintenance_months', 'grand_total', 'scope', 'features', 'costing', 'hosting', 'timeline',
        'deliverables', 'after_sales', 'published_snapshot', 'published_version', 'published_at',
        'published_by', 'has_unpublished_changes', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => ProposalType::class,
            'status' => ProposalStatus::class,
            'proposal_date' => 'date',
            'tax_note' => 'boolean',
            'maintenance_months' => 'integer',
            'grand_total' => 'decimal:2',
            'scope' => 'array',
            'features' => 'array',
            'costing' => 'array',
            'hosting' => 'array',
            'timeline' => 'array',
            'deliverables' => 'array',
            'after_sales' => 'array',
            'published_snapshot' => 'array',
            'published_version' => 'integer',
            'published_at' => 'datetime',
            'has_unpublished_changes' => 'boolean',
        ];
    }

    protected static function newFactory(): ProjectProposalFactory
    {
        return ProjectProposalFactory::new();
    }

    protected static function booted(): void
    {
        static::saving(function (self $proposal): void {
            $proposal->grand_total = (string) self::computeGrandTotal($proposal->costing ?? []);

            if ($proposal->exists
                && $proposal->getOriginal('status') === ProposalStatus::Published
                && ! $proposal->isDirty('published_snapshot')
                && $proposal->isDirty(self::CONTENT_FIELDS)) {
                $proposal->has_unpublished_changes = true;
            }
        });
    }

    /**
     * @param  list<array<string, mixed>>  $costing
     */
    public static function computeGrandTotal(array $costing): float
    {
        return round(array_sum(array_map(fn (array $row): float => (float) ($row['price'] ?? 0), $costing)), 2);
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
}
