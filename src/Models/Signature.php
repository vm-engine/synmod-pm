<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use VmEngine\Pm\Factories\SignatureFactory;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class Signature extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;

    protected $fillable = [
        'name',
        'position',
        'file',
    ];

    protected static function newFactory(): SignatureFactory
    {
        return SignatureFactory::new();
    }

    /**
     * Get document issuers that use this signature
     */
    public function issuers(): BelongsToMany
    {
        return $this->belongsToMany(
            DocumentIssuer::class,
            'issuer_signatures',
            'signature_id',
            'issuer_id'
        )->withTimestamps();
    }
}
