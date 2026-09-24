<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use VmEngine\Pm\Factories\ClientFactory;
use VmEngine\Pm\Models\Concerns\ResolvesUserModel;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Models\User;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property array<int, int|string>|null $services Service ids (JSON column)
 */
class Client extends Model
{
    use HasCreator;

    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    use HasUpdater;
    use ResolvesUserModel;
    use WithDeleteToken;

    // Status constants
    public const STATUS_PROSPECT = 0;

    public const STATUS_ACTIVE = 1;

    protected $fillable = [
        'user_id',
        'client_category_id',
        'company',
        'address',
        'city',
        'director',
        'pic',
        'phone',
        'services',
        'social_media',
        'npwp_image',
        'status',
        'active',
        'is_proposal',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'services' => 'array',
            'social_media' => 'array',
            'status' => 'integer',
            'active' => 'boolean',
            'is_proposal' => 'boolean',
        ];
    }

    /**
     * Get the category for this client.
     *
     * @return BelongsTo<ClientCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ClientCategory::class, 'client_category_id');
    }

    /**
     * Get the user that owns this client.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::userModel());
    }

    /**
     * Get the projects for this client.
     *
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id', 'user_id');
    }

    /**
     * Get project categories by this client
     *
     * @return HasManyThrough<ProjectCategory, Project, $this>
     */
    public function projectCategories(): HasManyThrough
    {
        return $this->hasManyThrough(ProjectCategory::class, Project::class, 'client_id', 'id', 'id', 'project_category_id')->distinct();
    }

    /**
     * Get the documents for this client.
     *
     * @return HasMany<ProjectDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    /**
     * Check if client is a prospect.
     */
    public function isProspect(): bool
    {
        return $this->status === self::STATUS_PROSPECT;
    }

    /**
     * Check if client is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Scope a query to only include prospect clients.
     *
     * @param  Builder<Client>  $query
     * @return Builder<Client>
     */
    public function scopeProspect(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PROSPECT);
    }

    /**
     * Scope a query to only include active clients.
     *
     * @param  Builder<Client>  $query
     * @return Builder<Client>
     */
    public function scopeActiveStatus(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include active (not disabled) clients.
     *
     * @param  Builder<Client>  $query
     * @return Builder<Client>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include inactive (disabled) clients.
     *
     * @param  Builder<Client>  $query
     * @return Builder<Client>
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Scope a query to search across multiple client fields.
     *
     * @param  Builder<Client>  $builder
     */
    #[Scope]
    protected function search(Builder $builder, string $q): void
    {
        $builder->whereAny([
            'company', 'address', 'city', 'director', 'pic',
        ], 'like', "%{$q}%");
    }

    /**
     * Scope a query to filter by services (ANY match).
     *
     * @param  Builder<Client>  $builder
     * @param  array<int, int|string>  $services
     */
    #[Scope]
    protected function byServiceAny(Builder $builder, array $services): void
    {
        if (empty($services)) {
            return;
        }

        $builder->where(function (Builder $q) use ($services) {
            foreach ($services as $service) {
                $q->orWhereJsonContains('services', (int) $service);
            }
        });
    }

    /**
     * Scope a query to filter by project category (ANY match).
     *
     * @param  Builder<Client>  $builder
     * @param  array<int, int|string>  $categoryIds
     */
    #[Scope]
    protected function byProjectCategoryAny(Builder $builder, array $categoryIds): void
    {
        if (empty($categoryIds)) {
            return;
        }

        $builder->whereHas('projectCategories', function (Builder $q) use ($categoryIds) {
            $q->whereIn('project_categories.id', $categoryIds);
        });
    }

    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();

        $this->user->status = 1;
        $this->user->save();
    }
}
