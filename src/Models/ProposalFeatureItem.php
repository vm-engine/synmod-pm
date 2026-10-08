<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $default_checked
 * @property int $sort
 * @property bool $active
 */
class ProposalFeatureItem extends Model
{
    use HasCreator;
    use HasUpdater;
    use WithDeleteToken;

    protected $fillable = ['name', 'description', 'default_checked', 'sort', 'active', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['default_checked' => 'boolean', 'sort' => 'integer', 'active' => 'boolean'];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
