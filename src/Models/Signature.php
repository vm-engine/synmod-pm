<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Factories\SignatureFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property string $name
 * @property string $position
 * @property string $file
 * @property int $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Signature extends Model
{
    use HasCreator;

    /** @use HasFactory<SignatureFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'position',
        'file',
    ];

    protected static function newFactory(): SignatureFactory
    {
        return SignatureFactory::new();
    }
}
