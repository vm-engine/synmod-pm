<?php

declare(strict_types=1);

namespace VmEngine\Pm\Enums;

enum ProposalStatus: int
{
    case Draft = 0;
    case Published = 1;

    public function label(): string
    {
        return match ($this) {
            self::Draft => __('pm::proposal.status_draft'),
            self::Published => __('pm::proposal.status_published'),
        };
    }
}
