<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\MaintenanceType;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\RiskLevel;
use VmEngine\Pm\Enums\SalesSources;
use VmEngine\Pm\Factories\ProjectFactory;
use VmEngine\Pm\Models\Concerns\ResolvesUserModel;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Models\User;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property int $user_id
 * @property int $division_id
 * @property string $name
 * @property string|null $description
 * @property int $year
 * @property array<array-key, mixed>|null $sub_division
 * @property SalesSources $source_of_sales
 * @property string|null $url
 * @property int $old_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $nominal
 * @property int|null $client_id
 * @property array<array-key, mixed>|null $services
 * @property int $progress
 * @property string|null $client_pic
 * @property string|null $client_pic_phone
 * @property Carbon|null $project_start
 * @property Carbon|null $project_end
 * @property string|null $manager
 * @property string|null $budget
 * @property string|null $down_payment
 * @property int $total_terms
 * @property string|null $payment_terms
 * @property Carbon|null $contract_signed_at
 * @property Carbon|null $client_approved_at
 * @property ProjectPriority $priority
 * @property RiskLevel|null $risk_level
 * @property int|null $project_manager_id
 * @property array<array-key, mixed>|null $assigned_team
 * @property array<array-key, mixed>|null $tags
 * @property Carbon|null $planned_delivery_date
 * @property Carbon|null $actual_delivery_date
 * @property Carbon|null $warranty_end_date
 * @property string|null $staging_url
 * @property string|null $production_url
 * @property string|null $repository_url
 * @property array<array-key, mixed>|null $server_info
 * @property MaintenanceType|null $maintenance_type
 * @property int|null $quality_score
 * @property int|null $client_satisfaction
 * @property ProjectStatus $status
 * @property int|null $project_category_id
 * @property-read ProjectProposal|null $proposal
 * @property-read ProjectSph|null $sph
 * @property-read ProjectSpk|null $spk
 */
class Project extends Model
{
    use HasCreator;

    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use HasUpdater;
    use ResolvesUserModel;
    use WithDeleteToken;

    protected $fillable = [
        'user_id',
        'division_id',
        'project_category_id',
        'client_id',
        'project_manager_id',
        'name',
        'description',
        'year',
        'sub_division',
        'nominal',
        'budget',
        'down_payment',
        'total_terms',
        'payment_terms',
        'source_of_sales',
        'services',
        'tags',
        'url',
        'staging_url',
        'production_url',
        'repository_url',
        'status',
        'progress',
        'priority',
        'risk_level',
        'client_pic',
        'client_pic_phone',
        'project_start',
        'project_end',
        'planned_delivery_date',
        'actual_delivery_date',
        'contract_signed_at',
        'client_approved_at',
        'warranty_end_date',
        'maintenance_type',
        'quality_score',
        'client_satisfaction',
        'manager',
        'assigned_team',
        'server_info',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sub_division' => 'array',
            'services' => 'array',
            'tags' => 'array',
            'assigned_team' => 'array',
            'server_info' => 'array',
            'year' => 'integer',
            'nominal' => 'decimal:2',
            'budget' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'source_of_sales' => SalesSources::class,
            'status' => ProjectStatus::class,
            'priority' => ProjectPriority::class,
            'maintenance_type' => MaintenanceType::class,
            'risk_level' => RiskLevel::class,
            'progress' => 'integer',
            'quality_score' => 'integer',
            'client_satisfaction' => 'integer',
            'project_start' => 'date',
            'project_end' => 'date',
            'planned_delivery_date' => 'date',
            'actual_delivery_date' => 'date',
            'contract_signed_at' => 'datetime',
            'client_approved_at' => 'datetime',
            'warranty_end_date' => 'date',
        ];
    }

    /**
     * Get the user that owns this project.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::userModel());
    }

    /**
     * Get the division for this project.
     *
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the project category for this project.
     *
     * @return BelongsTo<ProjectCategory, $this>
     */
    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    /**
     * Alias
     *
     * @return BelongsTo<ProjectCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->projectCategory();
    }

    /**
     * Get the client for this project.
     *
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the project manager for this project.
     *
     * @return BelongsTo<User, $this>
     */
    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(static::userModel(), 'project_manager_id');
    }

    /**
     * Get the documents for this project.
     *
     * @return HasMany<ProjectDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    /**
     * @return HasOne<ProjectProposal, $this>
     */
    public function proposal(): HasOne
    {
        return $this->hasOne(ProjectProposal::class);
    }

    /**
     * @return HasOne<ProjectSph, $this>
     */
    public function sph(): HasOne
    {
        return $this->hasOne(ProjectSph::class);
    }

    /**
     * @return HasOne<ProjectSpk, $this>
     */
    public function spk(): HasOne
    {
        return $this->hasOne(ProjectSpk::class);
    }

    /**
     * Search projects by query.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeSearch(Builder $query, string $q): Builder
    {
        return $query->whereAny([
            'name',
            'description',
            'manager',
            'client_pic',
        ], 'like', "%{$q}%");
    }

    /**
     * Filter projects by status.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeByStatus(Builder $query, ProjectStatus|int $status): Builder
    {
        $statusValue = $status instanceof ProjectStatus ? $status->value : $status;

        return $query->where('status', $statusValue);
    }

    /**
     * Filter projects by priority.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeByPriority(Builder $query, ProjectPriority|int $priority): Builder
    {
        $priorityValue = $priority instanceof ProjectPriority ? $priority->value : $priority;

        return $query->where('priority', $priorityValue);
    }

    /**
     * Filter projects by service (ANY match).
     *
     * @param  Builder<Project>  $query
     * @param  array<int, int|string>  $services
     * @return Builder<Project>
     */
    public function scopeByServiceAny(Builder $query, array $services): Builder
    {
        if (empty($services)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($services) {
            foreach ($services as $service) {
                $q->orWhereJsonContains('services', (int) $service);
            }
        });
    }

    /**
     * Scope a query to filter by project category (ANY match).
     *
     * @param  Builder<Project>  $query
     * @param  array<int, int|string>  $categoryIds
     * @return Builder<Project>
     */
    public function scopeByProjectCategoryAny(Builder $query, array $categoryIds): Builder
    {
        if (empty($categoryIds)) {
            return $query;
        }

        return $query->whereIn('project_category_id', $categoryIds);
    }

    /**
     * Get overdue projects.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereDate('planned_delivery_date', '<', now())
            ->whereNull('actual_delivery_date')
            ->whereNotIn('status', [
                ProjectStatus::COMPLETED->value,
                ProjectStatus::CANCELLED->value,
            ]);
    }

    /**
     * Get active projects.
     *
     * @param  Builder<Project>  $query
     * @return Builder<Project>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            ProjectStatus::PLANNING->value,
            ProjectStatus::IN_PROGRESS->value,
            ProjectStatus::ON_HOLD->value,
        ]);
    }

    /**
     * Check if project is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->planned_delivery_date
            && now()->greaterThan($this->planned_delivery_date)
            && ! $this->actual_delivery_date;
    }

    /**
     * Check if project is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === ProjectStatus::COMPLETED;
    }

    /**
     * Get progress percentage.
     */
    public function progressPercentage(): int
    {
        return $this->progress ?? 0;
    }

    public function hasProposalDocument(): bool
    {
        return $this->documents()->byType(Documents::PROPOSAL)->exists();
    }

    public function hasSPHDocument(): bool
    {
        return $this->documents()->byType(Documents::SPH)->exists();
    }

    public function hasSPKDocument(): bool
    {
        return $this->documents()->byType(Documents::SPK)->exists();
    }

    public function hasInvoiceDocument(): bool
    {
        return $this->documents()->byType(Documents::INVOICE)->exists();
    }

    /**
     * @return Attribute<string, string>
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => ucwords($value),
        );
    }
}
