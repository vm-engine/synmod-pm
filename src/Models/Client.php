<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use VmEngine\Pm\Factories\ClientFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class Client extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;
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
        'status',
        'active',
        'is_proposal',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
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
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ClientCategory::class, 'client_category_id');
    }

    /**
     * Get the user that owns this client.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the user who created this client.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Get the user who last updated this client.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
    }

    /**
     * Get the projects for this client.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id', 'user_id');
    }

    /**
     * Get project categories by this client
     */
    public function projectCategories(): HasManyThrough
    {
        return $this->hasManyThrough(ProjectCategory::class, Project::class, 'client_id', 'id', 'id', 'project_category_id')->distinct();
    }

    /**
     * Get the documents for this client.
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
     */
    public function scopeProspect($query)
    {
        return $query->where('status', self::STATUS_PROSPECT);
    }

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActiveStatus($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include active (not disabled) clients.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include inactive (disabled) clients.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Scope a query to search across multiple client fields.
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

    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->save();

        $this->user->status = 1;
        $this->user->save();
    }
}
