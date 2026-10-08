<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form\Concerns;

use Carbon\CarbonImmutable;
use Livewire\Attributes\Computed;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\ScheduledTask;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;
use VmEngine\Pm\Services\Proposal\ProposalFactory;

/**
 * Row editing for proposal sections, shared by the proposal form and the defaults editor.
 * The using component declares $costing, $hosting, $deliverables, $afterSales, $timeline
 * (and, for the proposal form, $features and $scope).
 */
trait EditsProposalSections
{
    /** @var array<string, list<string>> */
    public array $tabErrors = [];

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

    public function addChild(string $list, int $index): void
    {
        abort_unless(in_array($list, ['features', 'scope.menu'], true), 400);
        $rows = $this->getList($list);
        abort_unless(isset($rows[$index]), 404);
        $rows[$index]['children'][] = ['id' => ProposalFactory::newRowId(), 'name' => '', 'description' => ''];
        $this->setList($list, $rows);
    }

    public function removeChild(string $list, int $index, int $child): void
    {
        abort_unless(in_array($list, ['features', 'scope.menu'], true), 400);
        $rows = $this->getList($list);
        unset($rows[$index]['children'][$child]);
        $rows[$index]['children'] = array_values($rows[$index]['children'] ?? []);
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

    #[Computed]
    public function grandTotal(): float
    {
        return ProjectProposal::computeGrandTotal(array_map(
            fn (array $row): array => ['price' => self::parseAmount($row['price'] ?? 0)],
            $this->costing,
        ));
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
     * @param  list<array<string, mixed>>  $costing
     * @param  list<array<string, mixed>>  $hosting
     * @param  list<array<string, mixed>>  $deliverables
     * @param  list<array<string, mixed>>  $afterSales
     */
    protected function loadSections(array $costing, array $hosting, array $deliverables, array $afterSales): void
    {
        $id = fn (array $row): string => (string) ($row['id'] ?? ProposalFactory::newRowId());
        $this->costing = array_map(fn (array $r): array => [
            'id' => $id($r),
            'section' => (string) ($r['section'] ?? ''),
            'features' => implode("\n", (array) ($r['features'] ?? [])),
            'price' => (float) ($r['price'] ?? 0),
        ], $costing);
        $this->hosting = array_map(fn (array $r): array => [
            'id' => $id($r),
            'name' => (string) ($r['name'] ?? ''),
            'specs' => implode("\n", (array) ($r['specs'] ?? [])),
            'price' => (float) ($r['price'] ?? 0),
            'period' => (string) ($r['period'] ?? 'tahun'),
            'optional' => (bool) ($r['optional'] ?? false),
            'start_from' => (bool) ($r['start_from'] ?? false),
        ], $hosting);
        $this->deliverables = array_map(fn (array $r): array => [
            'id' => $id($r),
            'item' => (string) ($r['item'] ?? ''),
            'description' => (string) ($r['description'] ?? ''),
        ], $deliverables);
        $this->afterSales = array_map(fn (array $r): array => [
            'id' => $id($r),
            'label' => (string) ($r['label'] ?? ''),
        ], $afterSales);
    }

    /**
     * @return array{costing: list<array<string, mixed>>, hosting: list<array<string, mixed>>, deliverables: list<array<string, mixed>>, after_sales: list<array<string, mixed>>, timeline_tasks: list<array<string, mixed>>}
     */
    protected function sectionsForStorage(): array
    {
        $lines = fn (mixed $value): array => is_array($value)
            ? array_values($value)
            : array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $value) ?: []), fn (string $line): bool => $line !== ''));

        return [
            'costing' => array_map(fn (array $r): array => [
                'id' => (string) $r['id'],
                'section' => trim((string) $r['section']),
                'features' => $lines($r['features'] ?? ''),
                'price' => self::parseAmount($r['price'] ?? 0),
            ], array_values($this->costing)),
            'hosting' => array_map(fn (array $r): array => [
                'id' => (string) $r['id'],
                'name' => trim((string) $r['name']),
                'specs' => $lines($r['specs'] ?? ''),
                'price' => self::parseAmount($r['price'] ?? 0),
                'period' => trim((string) ($r['period'] ?? '')) !== '' ? trim((string) $r['period']) : 'tahun',
                'optional' => (bool) ($r['optional'] ?? false),
                'start_from' => (bool) ($r['start_from'] ?? false),
            ], array_values($this->hosting)),
            'deliverables' => array_map(fn (array $r): array => [
                'id' => (string) $r['id'],
                'item' => trim((string) $r['item']),
                'description' => trim((string) ($r['description'] ?? '')),
            ], array_values($this->deliverables)),
            'after_sales' => array_map(fn (array $r): array => [
                'id' => (string) $r['id'],
                'label' => trim((string) $r['label']),
            ], array_values($this->afterSales)),
            'timeline_tasks' => GanttScheduler::normalize($this->timeline['tasks'] ?? []),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function blankRow(string $list): array
    {
        return match ($list) {
            'costing' => ['section' => '', 'features' => '', 'price' => 0],
            'hosting' => ['name' => '', 'specs' => '', 'price' => 0, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
            'deliverables' => ['item' => '', 'description' => ''],
            'afterSales' => ['label' => ''],
            'features', 'scope.menu' => ['name' => '', 'description' => '', 'children' => []],
            'timeline.tasks' => ['name' => '', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => null],
            default => abort(400),
        };
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getList(string $list): array
    {
        return match ($list) {
            'costing' => $this->costing,
            'hosting' => $this->hosting,
            'deliverables' => $this->deliverables,
            'afterSales' => $this->afterSales,
            'features' => $this->features,
            'timeline.tasks' => $this->timeline['tasks'] ?? [],
            'scope.menu' => $this->scope['menu'] ?? [],
            default => abort(400),
        };
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function setList(string $list, array $rows): void
    {
        $rows = array_values($rows);
        match ($list) {
            'costing' => $this->costing = $rows,
            'hosting' => $this->hosting = $rows,
            'deliverables' => $this->deliverables = $rows,
            'afterSales' => $this->afterSales = $rows,
            'features' => $this->features = $rows,
            'timeline.tasks' => $this->timeline['tasks'] = GanttScheduler::normalize($rows),
            'scope.menu' => $this->scope['menu'] = $rows,
            default => abort(400),
        };
        unset($this->grandTotal, $this->scheduledTasks);
    }
}
