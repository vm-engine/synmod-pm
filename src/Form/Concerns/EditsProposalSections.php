<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form\Concerns;

use Livewire\Attributes\Computed;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\ProposalFactory;

/**
 * Proposal-specific sections (costing, hosting, deliverables, after-sales, features, menu), shared by the
 * proposal form and the defaults editor; rows and timeline come from EditsRowsAndTimeline.
 * The using component declares $costing, $hosting, $deliverables, $afterSales, $timeline
 * (and, for the proposal form, $features and $scope).
 */
trait EditsProposalSections
{
    use EditsRowsAndTimeline;

    /** @var array<string, list<string>> */
    public array $tabErrors = [];

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

    #[Computed]
    public function grandTotal(): float
    {
        return ProjectProposal::computeGrandTotal(array_map(
            fn (array $row): array => ['price' => self::parseAmount($row['price'] ?? 0)],
            $this->costing,
        ));
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
    protected function blankRow(string $list): array
    {
        return match ($list) {
            'costing' => ['section' => '', 'features' => '', 'price' => 0],
            'hosting' => ['name' => '', 'specs' => '', 'price' => 0, 'period' => 'tahun', 'optional' => true, 'start_from' => false],
            'deliverables' => ['item' => '', 'description' => ''],
            'afterSales' => ['label' => ''],
            'features', 'scope.menu' => ['name' => '', 'description' => '', 'children' => []],
            'timeline.tasks' => $this->blankTaskRow(),
            default => abort(400),
        };
    }
}
