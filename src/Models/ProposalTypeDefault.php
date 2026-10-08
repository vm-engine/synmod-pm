<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Model;
use VmEngine\Pm\Enums\ProposalType;

/**
 * @property int $id
 * @property ProposalType $type
 * @property list<array<string, mixed>> $costing
 * @property list<array<string, mixed>> $hosting
 * @property list<array<string, mixed>> $timeline_tasks
 * @property list<array<string, mixed>> $deliverables
 * @property list<array<string, mixed>> $after_sales
 */
class ProposalTypeDefault extends Model
{
    protected $fillable = ['type', 'costing', 'hosting', 'timeline_tasks', 'deliverables', 'after_sales', 'updated_by'];

    protected function casts(): array
    {
        return [
            'type' => ProposalType::class,
            'costing' => 'array',
            'hosting' => 'array',
            'timeline_tasks' => 'array',
            'deliverables' => 'array',
            'after_sales' => 'array',
        ];
    }

    /**
     * @return array{costing: list<array<string, mixed>>, hosting: list<array<string, mixed>>, timeline_tasks: list<array<string, mixed>>, deliverables: list<array<string, mixed>>, after_sales: list<array<string, mixed>>}
     */
    public function sections(): array
    {
        return [
            'costing' => $this->costing,
            'hosting' => $this->hosting,
            'timeline_tasks' => $this->timeline_tasks,
            'deliverables' => $this->deliverables,
            'after_sales' => $this->after_sales,
        ];
    }
}
