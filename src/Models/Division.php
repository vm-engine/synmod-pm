<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Factories\DivisionFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 * @property string|null $code
 */
class Division extends Model
{
    use HasCreator;

    /** @use HasFactory<DivisionFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'code',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): DivisionFactory
    {
        return DivisionFactory::new();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($division) {
            if (! empty($division->code)) {
                $division->code = strtoupper($division->code);
            }
        });

        static::updating(function ($division) {
            if ($division->isDirty('code') && ! empty($division->code)) {
                $division->code = strtoupper($division->code);
            }
        });
    }

    /**
     * Get the sub divisions for this division.
     *
     * @return HasMany<SubDivision, $this>
     */
    public function subDivisions(): HasMany
    {
        return $this->hasMany(SubDivision::class);
    }

    /**
     * Get the projects for this division.
     *
     * @return HasMany<Project, $this>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
