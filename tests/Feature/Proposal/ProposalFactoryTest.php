<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProposalFeatureItem;
use VmEngine\Pm\Models\ProposalTypeDefault;
use VmEngine\Pm\Services\Proposal\ProposalFactory;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('a website draft loads the active checklist with default checks', function () {
    ProposalFeatureItem::query()->where('name', 'Modul Gallery')->update(['default_checked' => false]);
    ProposalFeatureItem::query()->where('name', 'Modul Weblink')->update(['active' => false]);
    $project = Project::factory()->create();

    $proposal = app(ProposalFactory::class)->create($project, ProposalType::Website);

    expect($proposal->status)->toBe(ProposalStatus::Draft)
        ->and($proposal->title)->toBe('PEMBUATAN WEBSITE')
        ->and($proposal->subject)->toBe(strtoupper($project->client->company ?? ''))
        ->and($proposal->features)->toHaveCount(7)
        ->and(collect($proposal->features)->firstWhere('name', 'Modul Gallery')['checked'])->toBeFalse()
        ->and($proposal->scope)->toBe(['goal' => '', 'menu' => [], 'mockups' => []])
        ->and($proposal->timeline['tasks'])->toHaveCount(11);
});

test('an application draft starts with no modules and the PT ABS costing', function () {
    $proposal = app(ProposalFactory::class)->create(Project::factory()->create(), ProposalType::Application);

    expect($proposal->features)->toBe([])
        ->and($proposal->scope)->toBeNull()
        ->and((float) $proposal->grand_total)->toBe(220000000.0)
        ->and($proposal->title)->toBe('PEMBUATAN APLIKASI');
});

test('edited type defaults are used for new proposals', function () {
    $row = ProposalTypeDefault::query()->where('type', ProposalType::Application->value)->firstOrFail();
    $row->update(['costing' => [['id' => 'x', 'section' => 'Only', 'features' => [], 'price' => 1000]]]);

    $proposal = app(ProposalFactory::class)->create(Project::factory()->create(), ProposalType::Application);

    expect($proposal->costing)->toHaveCount(1)->and((float) $proposal->grand_total)->toBe(1000.0);
});

test('switching type replaces features and scope but keeps costing', function () {
    $factory = app(ProposalFactory::class);
    $proposal = $factory->create(Project::factory()->create(), ProposalType::Website);
    $proposal->update(['costing' => [['id' => 'k', 'section' => 'Kept', 'features' => [], 'price' => 5]]]);

    $switched = $factory->switchType($proposal, ProposalType::Application);

    expect($switched->type)->toBe(ProposalType::Application)
        ->and($switched->features)->toBe([])
        ->and($switched->scope)->toBeNull()
        ->and($switched->costing[0]['section'])->toBe('Kept');
});
