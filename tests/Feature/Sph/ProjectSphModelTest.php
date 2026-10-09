<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\ProjectSph;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('saving computes the grand total from the items', function () {
    $sph = ProjectSph::factory()->create();

    expect((float) $sph->grand_total)->toBe(20000000.0)
        ->and($sph->project->sph->is($sph))->toBeTrue();
});

test('editing content after publishing flags unpublished changes', function () {
    $sph = ProjectSph::factory()->create();
    $sph->forceFill(['status' => ProposalStatus::Published, 'published_snapshot' => ['x' => 1]])->save();
    $sph->forceFill(['has_unpublished_changes' => false])->saveQuietly();

    $sph->fresh()->update(['need' => 'Changed']);

    expect($sph->fresh()->has_unpublished_changes)->toBeTrue();
});
