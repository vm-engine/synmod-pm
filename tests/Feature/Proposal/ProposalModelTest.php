<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProposalFeatureItem;
use VmEngine\Pm\Models\ProposalTypeDefault;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('migrations seed eight website feature items and one default row per type', function () {
    expect(ProposalFeatureItem::count())->toBe(8)
        ->and(ProposalFeatureItem::query()->orderBy('sort')->first()->name)->toBe('Modul Static Page')
        ->and(ProposalTypeDefault::count())->toBe(2);

    $application = ProposalTypeDefault::query()->where('type', ProposalType::Application->value)->firstOrFail();
    expect(ProjectProposal::computeGrandTotal($application->sections()['costing']))->toBe(220000000.0);
});

test('grand total is recomputed from costing on save', function () {
    $proposal = ProjectProposal::factory()->create([
        'costing' => [
            ['id' => 'a', 'section' => 'Build', 'features' => [], 'price' => 60000000],
            ['id' => 'b', 'section' => 'Modules', 'features' => [], 'price' => 160000000],
        ],
    ]);

    expect((float) $proposal->fresh()->grand_total)->toBe(220000000.0)
        ->and($proposal->type)->toBe(ProposalType::Application)
        ->and($proposal->status)->toBe(ProposalStatus::Draft);
});

test('editing content of a published proposal flags unpublished changes', function () {
    $proposal = ProjectProposal::factory()->create(['status' => ProposalStatus::Published]);

    $proposal->update(['title' => 'NEW TITLE']);

    expect($proposal->fresh()->has_unpublished_changes)->toBeTrue();
});

test('editing a draft does not flag unpublished changes', function () {
    $proposal = ProjectProposal::factory()->create();

    $proposal->update(['title' => 'NEW TITLE']);

    expect($proposal->fresh()->has_unpublished_changes)->toBeFalse();
});

test('project has one proposal', function () {
    $proposal = ProjectProposal::factory()->create();

    expect(Project::find($proposal->project_id)->proposal->id)->toBe($proposal->id);
});

test('holiday dates are returned as Y-m-d strings', function () {
    Holiday::create(['date' => '2026-03-20', 'name' => 'Idul Fitri']);

    expect(Holiday::dates())->toBe(['2026-03-20']);
});
