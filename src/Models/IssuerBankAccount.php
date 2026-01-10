<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\IssuerBankAccountFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class IssuerBankAccount extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;

    protected $fillable = [
        'issuer_id',
        'bank_account_id',
    ];

    protected static function newFactory(): IssuerBankAccountFactory
    {
        return IssuerBankAccountFactory::new();
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(DocumentIssuer::class, 'issuer_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
