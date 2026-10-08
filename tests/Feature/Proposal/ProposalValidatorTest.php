<?php

declare(strict_types=1);

use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\ProposalValidator;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('a complete proposal has no errors', function () {
    expect((new ProposalValidator)->errors(ProjectProposal::factory()->create()))->toBe([]);
});

test('errors are grouped by tab', function () {
    $proposal = ProjectProposal::factory()->create([
        'title' => ' ',
        'features' => [],
        'costing' => [['id' => 'a', 'section' => 'A', 'features' => [], 'price' => -1]],
        'timeline' => ['start_date' => null, 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => []],
    ]);

    $errors = (new ProposalValidator)->errors($proposal);

    expect(array_keys($errors))->toBe(['general', 'features', 'costing', 'timeline'])
        ->and($errors['costing'])->toContain(__('pm::proposal.error_price_negative'))
        ->and($errors['costing'])->toContain(__('pm::proposal.error_total_zero'))
        ->and($errors['timeline'])->toHaveCount(2);
});

test('a website proposal needs at least one checked feature', function () {
    $proposal = ProjectProposal::factory()->website()->create();
    $proposal->update(['features' => array_map(fn (array $f): array => array_merge($f, ['checked' => false]), $proposal->features)]);

    expect((new ProposalValidator)->errors($proposal->fresh())['features'])->toBe([__('pm::proposal.error_features_empty')]);
});
