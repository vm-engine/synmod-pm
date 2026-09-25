<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Factories\BankAccountFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property string $name
 * @property string $number
 * @property string $bank_name
 * @property string|null $bank_logo
 * @property int $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $swift_code
 */
class BankAccount extends Model
{
    use HasCreator;

    /** @use HasFactory<BankAccountFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'number',
        'bank_name',
        'swift_code',
        'bank_logo',
    ];

    protected static function newFactory(): BankAccountFactory
    {
        return BankAccountFactory::new();
    }

    /**
     * Get the full URL for the bank logo
     */
    public function getBankLogoUrlAttribute(): ?string
    {
        return $this->bank_logo
            ? Storage::url($this->bank_logo)
            : null;
    }
}
