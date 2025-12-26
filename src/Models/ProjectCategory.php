<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use VmEngine\Pm\Factories\ProjectCategoryFactory;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

class ProjectCategory extends Model
{
    use HasCreator;
    use HasFactory;
    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = [
        'name',
        'code',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProjectCategoryFactory::new();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (! empty($category->code)) {
                $category->code = strtoupper($category->code);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('code') && ! empty($category->code)) {
                $category->code = strtoupper($category->code);
            }
        });
    }
}
