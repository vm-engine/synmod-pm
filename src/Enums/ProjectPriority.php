<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum ProjectPriority: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
    case URGENT = 4;

    public function label(): string
    {
        return match ($this) {
            ProjectPriority::LOW => 'Low',
            ProjectPriority::MEDIUM => 'Medium',
            ProjectPriority::HIGH => 'High',
            ProjectPriority::URGENT => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            ProjectPriority::LOW => 'gray',
            ProjectPriority::MEDIUM => 'blue',
            ProjectPriority::HIGH => 'orange',
            ProjectPriority::URGENT => 'red',
        };
    }
}
