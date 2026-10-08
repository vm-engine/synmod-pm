<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

use Carbon\CarbonImmutable;

final readonly class ScheduledTask
{
    public function __construct(
        public string $id,
        public int $number,
        public string $name,
        public ?string $parentId,
        public ?string $predecessorId,
        public bool $isSummary,
        public int $duration,
        public CarbonImmutable $start,
        public CarbonImmutable $finish,
    ) {}

    public function durationLabel(): string
    {
        return $this->duration.($this->duration === 1 ? ' day' : ' days');
    }

    public function level(): int
    {
        return $this->parentId === null ? 0 : 1;
    }
}
