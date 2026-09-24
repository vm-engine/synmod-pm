<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use VmEngine\Pm\Factories\ClientCategoryFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class ClientCategory extends Model
{
    use HasCreator;

    /** @use HasFactory<ClientCategoryFactory> */
    use HasFactory;

    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ClientCategoryFactory
    {
        return ClientCategoryFactory::new();
    }

    /**
     * Get the clients for this category.
     *
     * @return HasMany<Client, $this>
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
