<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VmEngine\Pm\Factories\DivisionFactory;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
     * Get the user who created this division.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Get the user who last updated this division.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
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
