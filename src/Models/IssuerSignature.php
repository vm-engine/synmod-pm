<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\IssuerSignatureFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class IssuerSignature extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;

    protected $fillable = [
        'issuer_id',
        'signature_id',
    ];

    protected static function newFactory(): IssuerSignatureFactory
    {
        return IssuerSignatureFactory::new();
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(DocumentIssuer::class, 'issuer_id');
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }
}
