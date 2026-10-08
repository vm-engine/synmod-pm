<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Renderer-agnostic Gantt geometry in column units. SVG and PPTX renderers scale it.
 */
final readonly class GanttLayout
{
    public const DAY_SCALE_MAX_WORKING_DAYS = 45;

    public const ROWS_PER_PAGE = 14;

    /**
     * @param  'day'|'week'  $scale
     * @param  list<GanttColumn>  $columns
     * @param  list<list<GanttRow>>  $pages
     */
    public function __construct(
        public string $scale,
        public array $columns,
        public array $pages,
        public ?CarbonImmutable $start,
        public ?CarbonImmutable $finish,
        public int $workingDays,
    ) {}

    /**
     * @param  list<ScheduledTask>  $tasks
     */
    public static function build(array $tasks, WorkCalendar $calendar, int $rowsPerPage = self::ROWS_PER_PAGE): self
    {
        if ($tasks === []) {
            return new self('day', [], [], null, null, 0);
        }

        $start = $tasks[0]->start;
        $finish = $tasks[0]->finish;
        foreach ($tasks as $task) {
            $start = $task->start->lt($start) ? $task->start : $start;
            $finish = $task->finish->gt($finish) ? $task->finish : $finish;
        }

        $workingDays = $calendar->workingDaysBetween($start, $finish);
        $scale = $workingDays <= self::DAY_SCALE_MAX_WORKING_DAYS ? 'day' : 'week';
        $rangeStart = $start->startOfWeek(CarbonInterface::MONDAY)->startOfDay();
        $rangeEnd = $finish->endOfWeek(CarbonInterface::SUNDAY)->startOfDay();
        $unitDays = $scale === 'day' ? 1 : 7;

        $columns = [];
        $week = 1;
        for ($date = $rangeStart; $date->lte($rangeEnd); $date = $date->addDays($unitDays)) {
            if ($scale === 'day') {
                $columns[] = new GanttColumn($date, substr($date->englishDayOfWeek, 0, 1), 'W'.$week, ! $calendar->isWorkingDay($date));
                if ($date->dayOfWeek === CarbonInterface::SUNDAY) {
                    $week++;
                }
            } else {
                $columns[] = new GanttColumn($date, 'W'.$week, $date->format("M 'y"), false);
                $week++;
            }
        }

        $rows = array_map(
            fn (ScheduledTask $task): GanttRow => new GanttRow(
                $task,
                (int) $rangeStart->diffInDays($task->start, true) / $unitDays,
                ((int) $task->start->diffInDays($task->finish, true) + 1) / $unitDays,
            ),
            $tasks,
        );

        return new self($scale, $columns, array_chunk($rows, max(1, $rowsPerPage)), $start, $finish, $workingDays);
    }
}
