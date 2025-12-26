<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum MaintenanceType: int
{
    case NONE = 0;
    case BASIC = 1;
    case PREMIUM = 2;

    public function label(): string
    {
        return match ($this) {
            MaintenanceType::NONE => 'No Maintenance',
            MaintenanceType::BASIC => 'Basic Maintenance',
            MaintenanceType::PREMIUM => 'Premium Maintenance',
        };
    }
}
