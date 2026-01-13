<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Factories\BankAccountFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class BankAccount extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'number',
        'bank_name',
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

    /**
     * Get document issuers that use this bank account
     */
    public function issuers(): BelongsToMany
    {
        return $this->belongsToMany(
            DocumentIssuer::class,
            'issuer_bank_accounts',
            'bank_account_id',
            'issuer_id'
        )->withTimestamps();
    }
}
