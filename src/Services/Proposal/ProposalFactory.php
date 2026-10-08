<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal;

use Illuminate\Support\Str;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProposalFeatureItem;
use VmEngine\Pm\Models\ProposalTypeDefault;

final class ProposalFactory
{
    public static function newRowId(): string
    {
        return Str::lower(Str::random(8));
    }

    public function create(Project $project, ProposalType $type): ProjectProposal
    {
        $project->loadMissing('client');
        $defaults = ProposalTypeDefault::query()->where('type', $type->value)->first()?->sections()
            ?? ProposalDefaults::for($type);

        return $project->proposal()->create([
            'type' => $type,
            'status' => ProposalStatus::Draft,
            'title' => $type === ProposalType::Website ? 'PEMBUATAN WEBSITE' : 'PEMBUATAN APLIKASI',
            'subject' => Str::upper((string) ($project->client->company ?? '')),
            'proposal_date' => now()->toDateString(),
            'tax_note' => true,
            'maintenance_months' => 3,
            'scope' => $this->defaultScope($type),
            'features' => $this->defaultFeatures($type),
            'costing' => $defaults['costing'],
            'hosting' => $defaults['hosting'],
            'timeline' => [
                'start_date' => now()->toDateString(),
                'extra_holidays' => [],
                'worked_holidays' => [],
                'tasks' => $defaults['timeline_tasks'],
            ],
            'deliverables' => $defaults['deliverables'],
            'after_sales' => $defaults['after_sales'],
        ]);
    }

    public function switchType(ProjectProposal $proposal, ProposalType $type): ProjectProposal
    {
        $proposal->update([
            'type' => $type,
            'scope' => $this->defaultScope($type),
            'features' => $this->defaultFeatures($type),
        ]);

        return $proposal;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function defaultFeatures(ProposalType $type): array
    {
        if ($type === ProposalType::Application) {
            return [];
        }

        return array_values(ProposalFeatureItem::query()->active()->orderBy('sort')->get()
            ->map(fn (ProposalFeatureItem $item): array => [
                'id' => self::newRowId(),
                'item_id' => $item->id,
                'name' => $item->name,
                'description' => (string) $item->description,
                'checked' => $item->default_checked,
                'sort' => $item->sort,
            ])
            ->all());
    }

    /**
     * @return array{goal: string, menu: list<array<string, mixed>>, mockups: list<array{path: string, caption: string}>}|null
     */
    private function defaultScope(ProposalType $type): ?array
    {
        return $type === ProposalType::Website ? ['goal' => '', 'menu' => [], 'mockups' => []] : null;
    }
}
