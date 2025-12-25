<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\SubDivisionFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class SubDivision extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'division_id',
        'name',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SubDivisionFactory::new();
    }

    /**
     * Get the division that owns this sub division.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the user who created this sub division.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Get the user who last updated this sub division.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
    }
}
