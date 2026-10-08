<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum ProposalType: int
{
    case Website = 1;
    case Application = 2;

    public function label(): string
    {
        return match ($this) {
            self::Website => __('pm::proposal.type_website'),
            self::Application => __('pm::proposal.type_application'),
        };
    }
}
