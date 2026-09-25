<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Factories\SubDivisionFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property int $division_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 */
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
