<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form\Concerns;

use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\GanttSvgRenderer;
use VmEngine\Pm\Services\Proposal\Gantt\ScheduledTask;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;
use VmEngine\Pm\Services\Proposal\ProposalFactory;

/**
 * Repeatable rows plus the timeline/Gantt editor, shared by the proposal form, the proposal defaults
 * editor and the SPH form. The using component declares a $timeline array, a #[Computed] grandTotal()
 * and blankRow() for every list it edits. A list name is a property path ('items', 'timeline.tasks').
 */
trait EditsRowsAndTimeline
{
    public string $newOffDay = '';

    /**
     * Blank row for $list; abort(400) for lists the component does not edit.
     *
     * @return array<string, mixed>
     */
    abstract protected function blankRow(string $list): array;

    public function addRow(string $list): void
    {
        $rows = $this->getList($list);
        $row = ['id' => ProposalFactory::newRowId()] + $this->blankRow($list);
        if ($list === 'timeline.tasks' && $rows !== []) {
            $row['predecessor_id'] = $rows[array_key_last($rows)]['id'];
        }
        $rows[] = $row;
        $this->setList($list, $rows);
    }

    public function removeRow(string $list, int $index): void
    {
        $rows = $this->getList($list);
        unset($rows[$index]);
        $this->setList($list, $rows);
    }

    public function moveRow(string $list, int $index, int $direction): void
    {
        $rows = $this->getList($list);
        $target = $index + ($direction < 0 ? -1 : 1);
        if (! isset($rows[$index], $rows[$target])) {
            return;
        }
        [$rows[$index], $rows[$target]] = [$rows[$target], $rows[$index]];
        $this->setList($list, $rows);
    }

    public function indentTask(int $index): void
    {
        $tasks = $this->timeline['tasks'] ?? [];
        if (! isset($tasks[$index])) {
            return;
        }
        $tasks[$index]['parent_id'] = 'indent';
        $this->timeline['tasks'] = GanttScheduler::normalize($tasks);
    }

    public function outdentTask(int $index): void
    {
        $tasks = $this->timeline['tasks'] ?? [];
        if (! isset($tasks[$index])) {
            return;
        }
        $tasks[$index]['parent_id'] = null;
        $this->timeline['tasks'] = GanttScheduler::normalize($tasks);
    }

    public function addOffDay(): void
    {
        $this->validate(['newOffDay' => 'required|date']);
        $date = CarbonImmutable::parse($this->newOffDay)->toDateString();
        $this->timeline['extra_holidays'] = array_values(array_unique([...$this->timeline['extra_holidays'], $date]));
        sort($this->timeline['extra_holidays']);
        $this->newOffDay = '';
        $this->forgetSchedule();
    }

    public function removeOffDay(int $index): void
    {
        unset($this->timeline['extra_holidays'][$index]);
        $this->timeline['extra_holidays'] = array_values($this->timeline['extra_holidays']);
        $this->forgetSchedule();
    }

    public function toggleWorkedHoliday(string $date): void
    {
        $worked = $this->timeline['worked_holidays'];
        $this->timeline['worked_holidays'] = in_array($date, $worked, true)
            ? array_values(array_diff($worked, [$date]))
            : [...$worked, $date];
        $this->forgetSchedule();
    }

    /**
     * @return list<ScheduledTask>
     */
    #[Computed]
    public function scheduledTasks(): array
    {
        $start = (string) ($this->timeline['start_date'] ?? '');

        return (new GanttScheduler)->schedule(
            $this->timeline['tasks'] ?? [],
            $start !== '' ? $start : CarbonImmutable::now()->toDateString(),
            $this->workCalendar(),
        );
    }

    /**
     * @return list<string> one SVG per Gantt page
     */
    #[Computed]
    public function ganttPreview(): array
    {
        $layout = GanttLayout::build($this->scheduledTasks, $this->workCalendar());
        $renderer = new GanttSvgRenderer;

        return array_map(fn (int $page): string => $renderer->render($layout, $page), array_keys($layout->pages));
    }

    /**
     * Global holidays between the first start and the last finish, with their "work this day" state.
     *
     * @return list<array{date: string, name: string, worked: bool}>
     */
    #[Computed]
    public function holidaysInSpan(): array
    {
        $tasks = $this->scheduledTasks;
        if ($tasks === []) {
            return [];
        }
        $start = min(array_map(fn ($t): string => $t->start->toDateString(), $tasks));
        $finish = max(array_map(fn ($t): string => $t->finish->toDateString(), $tasks));
        $worked = $this->timeline['worked_holidays'] ?? [];

        // Include worked holidays outside the computed span too, so they can be toggled back
        return Holiday::query()
            ->where(fn ($q) => $q->whereBetween('date', [$start, $finish])->orWhereIn('date', $worked))
            ->orderBy('date')
            ->get()
            ->map(fn (Holiday $h): array => [
                'date' => $h->date->toDateString(),
                'name' => $h->name,
                'worked' => in_array($h->date->toDateString(), $worked, true),
            ])
            ->values()
            ->all();
    }

    /** Amount from a number or an Indonesian-formatted string ("Rp. 15.000.000,50"). */
    public static function parseAmount(mixed $value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }
        $clean = preg_replace('/[^0-9,.\-]/', '', (string) $value) ?? '';
        if (str_contains($clean, ',')) {
            $clean = str_replace(['.', ','], ['', '.'], $clean);
        } elseif (substr_count($clean, '.') > 1 || preg_match('/\.\d{3}$/', $clean) === 1) {
            $clean = str_replace('.', '', $clean);
        }

        return (float) $clean;
    }

    protected function workCalendar(): WorkCalendar
    {
        return WorkCalendar::fromSets(
            Holiday::dates(),
            $this->timeline['extra_holidays'] ?? [],
            $this->timeline['worked_holidays'] ?? [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function blankTaskRow(): array
    {
        return ['name' => '', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => null];
    }

    protected function forgetSchedule(): void
    {
        unset($this->scheduledTasks, $this->ganttPreview, $this->holidaysInSpan);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getList(string $list): array
    {
        $this->blankRow($list); // rejects list names the component does not edit

        return array_values((array) data_get($this, $list, []));
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function setList(string $list, array $rows): void
    {
        $rows = array_values($rows);
        data_set($this, $list, $list === 'timeline.tasks' ? GanttScheduler::normalize($rows) : $rows);
        unset($this->grandTotal);
        $this->forgetSchedule();
    }
}
