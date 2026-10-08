<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use VmEngine\Synapse\Traits\WithDeleteToken;
use VmEngine\SynAuth\Traits\HasCreator;
use VmEngine\SynAuth\Traits\HasUpdater;

/**
 * @property int $id
 * @property Carbon $date
 * @property string $name
 */
class Holiday extends Model
{
    use HasCreator;
    use HasUpdater;
    use WithDeleteToken;

    protected $table = 'pm_holidays';

    protected $fillable = ['date', 'name', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    /**
     * @return list<string>
     */
    public static function dates(): array
    {
        return array_values(self::query()->orderBy('date')->get()
            ->map(fn (self $holiday): string => $holiday->date->toDateString())
            ->all());
    }
}
