<?php

declare(strict_types=1);

namespace VmEngine\Pm\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\ProposalDefaults;

/**
 * @extends Factory<ProjectProposal>
 */
class ProjectProposalFactory extends Factory
{
    protected $model = ProjectProposal::class;

    public function definition(): array
    {
        $defaults = ProposalDefaults::for(ProposalType::Application);

        return [
            'project_id' => Project::factory(),
            'type' => ProposalType::Application,
            'status' => ProposalStatus::Draft,
            'title' => 'PEMBUATAN APLIKASI',
            'subject' => 'PT CONTOH',
            'proposal_date' => '2026-01-08',
            'tax_note' => true,
            'maintenance_months' => 3,
            'scope' => null,
            'features' => [
                ['id' => 'm1', 'name' => 'Master Data', 'description' => '', 'children' => [
                    ['id' => 'm1a', 'name' => 'Warehouse Management', 'description' => ''],
                ]],
            ],
            'costing' => $defaults['costing'],
            'hosting' => $defaults['hosting'],
            'timeline' => ['start_date' => '2026-01-19', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => $defaults['timeline_tasks']],
            'deliverables' => $defaults['deliverables'],
            'after_sales' => $defaults['after_sales'],
        ];
    }

    public function website(): static
    {
        $defaults = ProposalDefaults::for(ProposalType::Website);

        return $this->state(fn (): array => [
            'type' => ProposalType::Website,
            'title' => 'PEMBUATAN WEBSITE',
            'scope' => ['goal' => '', 'menu' => [], 'mockups' => []],
            'features' => array_map(
                fn (array $item, int $i): array => ['id' => 'f'.$i, 'item_id' => null, 'name' => $item['name'], 'description' => $item['description'], 'checked' => true, 'sort' => $item['sort']],
                ProposalDefaults::featureItems(),
                array_keys(ProposalDefaults::featureItems()),
            ),
            'costing' => $defaults['costing'],
            'hosting' => $defaults['hosting'],
            'timeline' => ['start_date' => '2025-07-01', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => $defaults['timeline_tasks']],
            'deliverables' => $defaults['deliverables'],
            'after_sales' => $defaults['after_sales'],
        ]);
    }
}
