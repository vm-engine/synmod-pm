<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Factories\DocumentIssuerFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property string $name
 * @property string $header_image
 * @property string|null $footer_image
 * @property string $tax_id_name
 * @property string $tax_id_address
 * @property string $tax_id_number
 * @property int $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class DocumentIssuer extends Model
{
    use HasCreator;

    /** @use HasFactory<DocumentIssuerFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'header_image',
        'footer_image',
        'tax_id_name',
        'tax_id_address',
        'tax_id_number',
    ];

    protected static function newFactory(): DocumentIssuerFactory
    {
        return DocumentIssuerFactory::new();
    }

    /**
     * Get the full URL for the header image
     */
    public function getHeaderImageUrlAttribute(): ?string
    {
        return $this->header_image
            ? Storage::url($this->header_image)
            : null;
    }
}
