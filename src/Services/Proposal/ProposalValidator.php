<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal;

use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\ProjectProposal;

/**
 * Rules a proposal must pass before Preview or Publish (spec §6.4).
 */
final class ProposalValidator
{
    /**
     * @return array<string, list<string>> tab => messages, in tab order
     */
    public function errors(ProjectProposal $proposal): array
    {
        $proposal->loadMissing('project');
        $errors = ['general' => [], 'features' => [], 'costing' => [], 'timeline' => []];

        if (trim($proposal->title) === '') {
            $errors['general'][] = __('pm::proposal.error_title');
        }
        if ($proposal->project->client_id === null) {
            $errors['general'][] = __('pm::proposal.error_client');
        }

        $features = $proposal->features ?? [];
        if ($proposal->type === ProposalType::Application && $features === []) {
            $errors['features'][] = __('pm::proposal.error_modules_empty');
        }
        if ($proposal->type === ProposalType::Website && ! collect($features)->contains(fn (array $f): bool => (bool) ($f['checked'] ?? false))) {
            $errors['features'][] = __('pm::proposal.error_features_empty');
        }

        $costing = $proposal->costing ?? [];
        if ($costing === []) {
            $errors['costing'][] = __('pm::proposal.error_costing_empty');
        }
        if (collect($costing)->contains(fn (array $row): bool => (float) ($row['price'] ?? 0) < 0)) {
            $errors['costing'][] = __('pm::proposal.error_price_negative');
        }
        if ($costing !== [] && ProjectProposal::computeGrandTotal($costing) <= 0) {
            $errors['costing'][] = __('pm::proposal.error_total_zero');
        }

        $timeline = $proposal->timeline ?? [];
        if (empty($timeline['start_date'])) {
            $errors['timeline'][] = __('pm::proposal.error_start_date');
        }
        if (empty($timeline['tasks'])) {
            $errors['timeline'][] = __('pm::proposal.error_tasks_empty');
        }

        return array_filter($errors, fn (array $messages): bool => $messages !== []);
    }
}
