<?php

declare(strict_types=1);

use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Spk\SpkValidator;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('the factory SPK is valid', function () {
    expect(app(SpkValidator::class)->errors(ProjectSpk::factory()->create()))->toBe([]);
});

test('errors are grouped by tab', function () {
    $spk = ProjectSpk::factory()->create([
        'party1_representative' => '', 'party1_position' => ' ', 'issuer_id' => null, 'scope' => '', 'work_duration' => 0,
        'bank_account_id' => null, 'contacts' => ['first' => ['name' => ''], 'second' => ['name' => 'X']],
        'extra_pasal' => [['id' => 'x', 'title' => '', 'body' => 'text']],
    ]);

    expect(app(SpkValidator::class)->errors($spk))->toBe([
        'general' => [__('pm::spk.error_party1_representative'), __('pm::spk.error_party1_position'), __('pm::spk.error_issuer')],
        'scope' => [__('pm::spk.error_scope')],
        'duration' => [__('pm::spk.error_duration')],
        'payment' => [__('pm::spk.error_bank')],
        'terms' => [__('pm::spk.error_contacts')],
        'extra' => [__('pm::spk.error_extra')],
    ]);
});

test('installments must add up to the contract value', function () {
    $spk = ProjectSpk::factory()->create(['contract_value' => 200000000]);

    expect(app(SpkValidator::class)->errors($spk)['payment'])->toBe([
        __('pm::spk.error_installments_sum', ['total' => 'Rp178.000.000,-', 'value' => 'Rp200.000.000,-']),
    ]);
});
