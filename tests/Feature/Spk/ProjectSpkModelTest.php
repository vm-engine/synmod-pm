<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\ProjectSpk;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('factory creates an SPK reachable from its project', function () {
    $spk = ProjectSpk::factory()->create();

    expect($spk->project->spk->id)->toBe($spk->id)
        ->and($spk->status)->toBe(ProposalStatus::Draft)
        ->and($spk->installments)->toHaveCount(2)
        ->and($spk->bankAccount)->not->toBeNull();
});

test('installment amounts: percent of the contract value or a fixed amount, times count', function () {
    expect(ProjectSpk::installmentAmount(['type' => 'percent', 'value' => 30], 56000000))->toBe(16800000.0)
        ->and(ProjectSpk::installmentAmount(['type' => 'amount', 'value' => 13000000], 56000000))->toBe(13000000.0)
        ->and(ProjectSpk::installmentsTotal([
            ['type' => 'amount', 'value' => 35000000, 'count' => 1],
            ['type' => 'amount', 'value' => 13000000, 'count' => 11],
        ], 178000000))->toBe(178000000.0);
});

test('editing a published SPK flags unpublished changes', function () {
    $spk = ProjectSpk::factory()->create();
    $spk->forceFill(['status' => ProposalStatus::Published, 'published_snapshot' => ['x' => 1]])->save();
    $spk->forceFill(['has_unpublished_changes' => false])->saveQuietly();

    $spk->update(['title' => 'Changed']);

    expect($spk->fresh()->has_unpublished_changes)->toBeTrue();
});
