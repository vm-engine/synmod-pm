<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

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

    /** @use HasFactory<SubDivisionFactory> */
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
    protected static function newFactory(): SubDivisionFactory
    {
        return SubDivisionFactory::new();
    }

    /**
     * Get the division that owns this sub division.
     *
     * @return BelongsTo<Division, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
