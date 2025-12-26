<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum RiskLevel: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function label(): string
    {
        return match ($this) {
            RiskLevel::LOW => 'Low Risk',
            RiskLevel::MEDIUM => 'Medium Risk',
            RiskLevel::HIGH => 'High Risk',
        };
    }

    public function color(): string
    {
        return match ($this) {
            RiskLevel::LOW => 'green',
            RiskLevel::MEDIUM => 'yellow',
            RiskLevel::HIGH => 'red',
        };
    }
}
