<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;

test('a short project uses day columns from Monday of the first week to Sunday of the last', function () {
    $calendar = new WorkCalendar;
    $layout = GanttLayout::build((new GanttScheduler)->schedule(thinkTasks(), '2025-07-01', $calendar), $calendar);

    expect($layout->scale)->toBe('day')
        ->and($layout->workingDays)->toBe(23)
        ->and($layout->columns)->toHaveCount(35)
        ->and($layout->columns[0]->date->toDateString())->toBe('2025-06-30')
        ->and($layout->columns[0]->label)->toBe('M')
        ->and($layout->columns[5]->shaded)->toBeTrue()
        ->and($layout->pages[0][0]->barStart)->toBe(1.0)
        ->and($layout->pages[0][0]->barSpan)->toBe(1.0);
});

test('a long project uses week columns', function () {
    $calendar = new WorkCalendar;
    $layout = GanttLayout::build((new GanttScheduler)->schedule(absTasks(), '2026-01-19', $calendar), $calendar);
    $development = $layout->pages[0][4];

    expect($layout->scale)->toBe('week')
        ->and($layout->columns)->toHaveCount(18)
        ->and($layout->columns[0]->label)->toBe('W1')
        ->and($layout->columns[2]->groupLabel)->toBe("Feb '26")
        ->and($development->barStart)->toBe(4.0)
        ->and($development->barSpan)->toEqualWithDelta(82 / 7, 0.0001);
});

test('holidays are shaded on the day scale', function () {
    $calendar = WorkCalendar::fromSets(['2025-07-02']);
    $layout = GanttLayout::build((new GanttScheduler)->schedule(thinkTasks(), '2025-07-01', $calendar), $calendar);

    expect($layout->columns[2]->shaded)->toBeTrue();
});

test('rows are split into pages of fourteen', function () {
    $tasks = [];
    for ($i = 1; $i <= 30; $i++) {
        $tasks[] = ['id' => 't'.$i, 'name' => 'Task '.$i, 'parent_id' => null, 'duration' => 1, 'predecessor_id' => $i > 1 ? 't'.($i - 1) : null];
    }
    $calendar = new WorkCalendar;
    $layout = GanttLayout::build((new GanttScheduler)->schedule($tasks, '2026-01-19', $calendar), $calendar);

    expect(array_map('count', $layout->pages))->toBe([14, 14, 2]);
});

test('no tasks gives an empty layout', function () {
    $layout = GanttLayout::build([], new WorkCalendar);

    expect($layout->pages)->toBe([])->and($layout->start)->toBeNull();
});
