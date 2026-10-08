<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

use Carbon\CarbonImmutable;

final readonly class GanttColumn
{
    public function __construct(
        public CarbonImmutable $date,
        public string $label,
        public string $groupLabel,
        public bool $shaded,
    ) {}
}
