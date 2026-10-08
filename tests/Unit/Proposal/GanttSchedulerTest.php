<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\ScheduledTask;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;

/**
 * @param  list<ScheduledTask>  $tasks
 * @return array<string, array{0:string, 1:string, 2:int}>
 */
function dates(array $tasks): array
{
    $out = [];
    foreach ($tasks as $task) {
        $out[$task->id] = [$task->start->toDateString(), $task->finish->toDateString(), $task->duration];
    }

    return $out;
}

test('schedules the PT ABS timeline like MS Project', function () {
    $tasks = (new GanttScheduler)->schedule(absTasks(), '2026-01-19', new WorkCalendar);

    expect(dates($tasks))->toBe([
        't1' => ['2026-01-19', '2026-01-19', 1],
        't2' => ['2026-01-20', '2026-01-23', 4],
        't3' => ['2026-01-26', '2026-02-06', 10],
        't4' => ['2026-02-09', '2026-02-13', 5],
        't5' => ['2026-02-16', '2026-05-08', 60],
        't6' => ['2026-05-11', '2026-05-15', 5],
        't7' => ['2026-05-18', '2026-05-20', 3],
        't8' => ['2026-05-18', '2026-05-19', 2],
        't9' => ['2026-05-20', '2026-05-20', 1],
    ]);
    expect($tasks[6]->isSummary)->toBeTrue()
        ->and($tasks[7]->level())->toBe(1)
        ->and((new WorkCalendar)->workingDaysBetween($tasks[0]->start, $tasks[6]->finish))->toBe(88);
});

test('schedules the Think timeline with a summary that has a predecessor', function () {
    $tasks = (new GanttScheduler)->schedule(thinkTasks(), '2025-07-01', new WorkCalendar);
    $d = dates($tasks);

    expect($d['t4'])->toBe(['2025-07-04', '2025-07-18', 11])
        ->and($d['t5'])->toBe(['2025-07-04', '2025-07-10', 5])
        ->and($d['t6'])->toBe(['2025-07-11', '2025-07-18', 6])
        ->and($d['t7'])->toBe(['2025-07-21', '2025-07-28', 6])
        ->and($d['t11'])->toBe(['2025-07-31', '2025-07-31', 1]);
});

test('a holiday inside a task pushes its finish and everything after it', function () {
    $calendar = WorkCalendar::fromSets(['2026-03-20']);
    $d = dates((new GanttScheduler)->schedule(absTasks(), '2026-01-19', $calendar));

    expect($d['t5'])->toBe(['2026-02-16', '2026-05-11', 60])
        ->and($d['t9'])->toBe(['2026-05-21', '2026-05-21', 1]);
});

test('a worked holiday is a working day again', function () {
    $calendar = WorkCalendar::fromSets(['2026-03-20'], [], ['2026-03-20']);
    $d = dates((new GanttScheduler)->schedule(absTasks(), '2026-01-19', $calendar));

    expect($d['t9'])->toBe(['2026-05-20', '2026-05-20', 1]);
});

test('extra off days count as holidays', function () {
    $calendar = WorkCalendar::fromSets([], ['2026-01-20']);
    $d = dates((new GanttScheduler)->schedule(absTasks(), '2026-01-19', $calendar));

    expect($d['t2'])->toBe(['2026-01-21', '2026-01-26', 4]);
});

test('a start date on a weekend or holiday moves to the next working day', function () {
    $weekend = dates((new GanttScheduler)->schedule(absTasks(), '2026-01-17', new WorkCalendar));
    $holiday = dates((new GanttScheduler)->schedule(absTasks(), '2026-01-19', WorkCalendar::fromSets(['2026-01-19'])));

    expect($weekend['t1'][0])->toBe('2026-01-19')
        ->and($holiday['t1'][0])->toBe('2026-01-20');
});

test('normalize clears predecessors that point forward, to a parent, or to a missing row', function () {
    $tasks = GanttScheduler::normalize([
        ['id' => 'a', 'name' => 'A', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 'c'],
        ['id' => 'b', 'name' => 'B', 'parent_id' => 'a', 'duration' => 1, 'predecessor_id' => 'a'],
        ['id' => 'c', 'name' => 'C', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => 'gone'],
    ]);

    expect(array_column($tasks, 'predecessor_id'))->toBe([null, null, null]);
});

test('normalize attaches children to the nearest top-level row and keeps one level', function () {
    $tasks = GanttScheduler::normalize([
        ['id' => 'a', 'name' => 'A', 'parent_id' => 'x', 'duration' => '0', 'predecessor_id' => null],
        ['id' => 'b', 'name' => 'B', 'parent_id' => null, 'duration' => 2, 'predecessor_id' => null],
        ['id' => 'c', 'name' => 'C', 'parent_id' => 'zzz', 'duration' => 2, 'predecessor_id' => null],
        ['id' => 'd', 'name' => 'D', 'parent_id' => 'c', 'duration' => 2, 'predecessor_id' => null],
    ]);

    expect(array_column($tasks, 'parent_id'))->toBe([null, null, 'b', 'b'])
        ->and($tasks[0]['duration'])->toBe(1);
});

test('empty task list schedules to nothing', function () {
    expect((new GanttScheduler)->schedule([], '2026-01-19', new WorkCalendar))->toBe([]);
});
