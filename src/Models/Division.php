<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VmEngine\Pm\Factories\DivisionFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class Division extends Model
{
    use HasCreator;
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
    protected static function newFactory(): Factory
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
     */
    public function subDivisions(): HasMany
    {
        return $this->hasMany(SubDivision::class);
    }

    /**
     * Get the projects for this division.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
