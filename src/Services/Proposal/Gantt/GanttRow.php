<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

final readonly class GanttRow
{
    /**
     * @param  float  $barStart  offset from the first column, in columns
     * @param  float  $barSpan  bar length, in columns
     */
    public function __construct(
        public ScheduledTask $task,
        public float $barStart,
        public float $barSpan,
    ) {}
}
