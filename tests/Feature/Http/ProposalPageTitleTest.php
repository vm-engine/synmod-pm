<?php

declare(strict_types=1);

use VmEngine\Pm\Models\ProjectProposal;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->withoutMiddleware();
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('the proposal generator sets the browser title', function () {
    $proposal = ProjectProposal::factory()->create();

    get(route('backend.pm.proposal.edit', ['id' => $proposal->project_id]))
        ->assertOk()
        ->assertSee('<title>'.e(page_title(__('pm::proposal.page_title'))), false);
});

test('proposal master pages set the browser title', function (string $route, string $titleKey) {
    get(route($route))
        ->assertOk()
        ->assertSee('<title>'.e(page_title(__($titleKey))), false);
})->with([
    'holidays' => ['backend.pm.master.holiday', 'pm::proposal.holiday_title'],
    'features' => ['backend.pm.master.proposal_feature', 'pm::proposal.feature_items_title'],
    'defaults' => ['backend.pm.master.proposal_default', 'pm::proposal.defaults_title'],
]);
