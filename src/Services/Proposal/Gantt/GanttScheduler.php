<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

use Carbon\CarbonImmutable;

/**
 * Finish-to-start scheduling, one nesting level, like the MS Project screenshots in the reference decks.
 */
final class GanttScheduler
{
    /**
     * Enforce the invariants the scheduler relies on:
     * - a child's parent is the nearest preceding top-level row (so children sit directly below it);
     * - a predecessor is an earlier row and not the row's own parent (so no cycles);
     * - leaf duration ≥ 1.
     *
     * @param  array<int, array<string, mixed>>  $tasks  may be non-sequential after a row was removed
     * @return list<array{id:string, name:string, parent_id:?string, duration:int, predecessor_id:?string}>
     */
    public static function normalize(array $tasks): array
    {
        $normalized = [];
        $seen = [];
        $currentTop = null;

        foreach (array_values($tasks) as $task) {
            $id = (string) $task['id'];
            $requestedParent = $task['parent_id'] ?? null;
            $wantsParent = $requestedParent !== null && $requestedParent !== '';
            $parentId = $wantsParent && $currentTop !== null ? $currentTop : null;
            if ($parentId === null) {
                $currentTop = $id;
            }

            $predecessor = $task['predecessor_id'] ?? null;
            if ($predecessor === null || $predecessor === '' || ! isset($seen[$predecessor]) || $predecessor === $parentId) {
                $predecessor = null;
            }

            $normalized[] = [
                'id' => $id,
                'name' => (string) ($task['name'] ?? ''),
                'parent_id' => $parentId,
                'duration' => max(1, (int) ($task['duration'] ?? 1)),
                'predecessor_id' => $predecessor,
            ];
            $seen[$id] = true;
        }

        return $normalized;
    }

    /**
     * @param  list<array<string, mixed>>  $tasks
     * @return list<ScheduledTask>
     */
    public function schedule(array $tasks, string $startDate, WorkCalendar $calendar): array
    {
        $tasks = self::normalize($tasks);
        if ($tasks === []) {
            return [];
        }

        $projectStart = $calendar->nextWorkingDay(CarbonImmutable::parse($startDate));
        $hasChildren = [];
        foreach ($tasks as $task) {
            if ($task['parent_id'] !== null) {
                $hasChildren[$task['parent_id']] = true;
            }
        }

        /** @var array<string, CarbonImmutable> $starts */
        $starts = [];
        /** @var array<string, CarbonImmutable> $finishes */
        $finishes = [];
        /** @var array<string, CarbonImmutable> $anchors earliest start for a summary's children */
        $anchors = [];

        foreach ($tasks as $task) {
            $id = $task['id'];
            $start = $task['parent_id'] !== null ? $anchors[$task['parent_id']] : $projectStart;
            if ($task['predecessor_id'] !== null) {
                $start = $calendar->nextWorkingDay($finishes[$task['predecessor_id']]->addDay());
            }

            if (isset($hasChildren[$id])) {
                $anchors[$id] = $start;

                continue;
            }

            $finish = $calendar->addWorkingDays($start, $task['duration'] - 1);
            $starts[$id] = $start;
            $finishes[$id] = $finish;

            $parentId = $task['parent_id'];
            if ($parentId !== null) {
                $starts[$parentId] = isset($starts[$parentId]) ? $starts[$parentId]->min($start) : $start;
                $finishes[$parentId] = isset($finishes[$parentId]) ? $finishes[$parentId]->max($finish) : $finish;
            }
        }

        $scheduled = [];
        foreach ($tasks as $index => $task) {
            $id = $task['id'];
            $isSummary = isset($hasChildren[$id]);
            $scheduled[] = new ScheduledTask(
                id: $id,
                number: $index + 1,
                name: $task['name'],
                parentId: $task['parent_id'],
                predecessorId: $task['predecessor_id'],
                isSummary: $isSummary,
                duration: $isSummary ? $calendar->workingDaysBetween($starts[$id], $finishes[$id]) : $task['duration'],
                start: $starts[$id],
                finish: $finishes[$id],
            );
        }

        return $scheduled;
    }
}
