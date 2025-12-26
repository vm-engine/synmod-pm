<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum ProjectStatus: int
{
    case PROSPECT = 0;
    case PLANNING = 1;
    case IN_PROGRESS = 2;
    case ON_HOLD = 3;
    case COMPLETED = 4;
    case CANCELLED = 5;
    case MAINTENANCE = 6;

    public function label(): string
    {
        return match ($this) {
            ProjectStatus::PROSPECT => 'Prospect',
            ProjectStatus::PLANNING => 'Planning',
            ProjectStatus::IN_PROGRESS => 'In Progress',
            ProjectStatus::ON_HOLD => 'On Hold',
            ProjectStatus::COMPLETED => 'Completed',
            ProjectStatus::CANCELLED => 'Cancelled',
            ProjectStatus::MAINTENANCE => 'Maintenance',
        };
    }

    public function color(): string
    {
        return match ($this) {
            ProjectStatus::PROSPECT => 'orange',
            ProjectStatus::PLANNING => 'gray',
            ProjectStatus::IN_PROGRESS => 'blue',
            ProjectStatus::ON_HOLD => 'yellow',
            ProjectStatus::COMPLETED => 'green',
            ProjectStatus::CANCELLED => 'red',
            ProjectStatus::MAINTENANCE => 'purple',
        };
    }
}
