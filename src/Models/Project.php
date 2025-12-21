<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use VmEngine\Pm\Factories\ProjectFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'division_id',
        'name',
        'description',
        'year',
        'sub_division',
        'type_of_client',
        'pricing',
        'source_of_sales',
        'url',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProjectFactory::new();
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sub_division' => 'array',
            'year' => 'integer',
            'type_of_client' => 'integer',
            'pricing' => 'integer',
            'source_of_sales' => 'integer',
            'status' => 'integer',
        ];
    }

    /**
     * Get the user that owns this project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the division for this project.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the user who created this project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    /**
     * Get the user who last updated this project.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
    }

    /**
     * Get the client for this project through user relationship.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'user_id', 'user_id');
    }
}
