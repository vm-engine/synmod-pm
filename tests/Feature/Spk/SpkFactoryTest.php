<?php

declare(strict_types=1);

use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Spk\SpkDefaults;
use VmEngine\Pm\Services\Spk\SpkFactory;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('a new SPK is prefilled from the client and defaults', function () {
    $client = Client::factory()->create(['company' => 'PT Joob Global Indonesia', 'address' => 'WeWork Noble House', 'city' => 'Jakarta', 'director' => 'Imaduddin Amin', 'phone' => '0812']);
    $project = Project::factory()->create(['client_id' => $client->id, 'name' => 'Layanan Jasa Digital Marketing', 'nominal' => 106800000]);
    BankAccount::factory()->create();

    $spk = app(SpkFactory::class)->create($project);

    expect($spk->title)->toBe('Layanan Jasa Digital Marketing')
        ->and($spk->party1_company)->toBe('PT Joob Global Indonesia')
        ->and($spk->party1_address)->toBe("WeWork Noble House\nJakarta")
        ->and($spk->party1_representative)->toBe('Imaduddin Amin')
        ->and($spk->asas)->toBe(SpkDefaults::ASAS)
        ->and((float) $spk->contract_value)->toBe(106800000.0)
        ->and($spk->installments)->toHaveCount(2)
        ->and($spk->installments[0]['value'])->toEqual(30)
        ->and($spk->bank_account_id)->not->toBeNull()
        ->and($spk->contacts['first']['phone'])->not->toBe('');
});

test('party two, signer and bank carry over from the last published SPK', function () {
    $previous = ProjectSpk::factory()->create(['published_at' => now(), 'party2_address' => 'Bandung', 'party2_description' => 'perusahaan IT']);

    $spk = app(SpkFactory::class)->create(Project::factory()->create());

    expect($spk->party2_address)->toBe('Bandung')
        ->and($spk->party2_description)->toBe('perusahaan IT')
        ->and($spk->signature_id)->toBe($previous->signature_id)
        ->and($spk->bank_account_id)->toBe($previous->bank_account_id)
        ->and($spk->contacts['second'])->toBe($previous->contacts['second']);
});

test('scope and duration come from the proposal, value and DP from the SPH', function () {
    $proposal = ProjectProposal::factory()->create();
    ProjectSph::factory()->create(['project_id' => $proposal->project_id, 'dp_percentage' => 40, 'payment_enabled' => true]);

    $spk = app(SpkFactory::class)->create($proposal->project);

    expect($spk->scope)->toStartWith('- ')
        ->and($spk->work_duration)->toBe(88)
        ->and((float) $spk->contract_value)->toBe(20000000.0)
        ->and($spk->installments[0]['value'])->toEqual(40)
        ->and($spk->installments[1]['value'])->toEqual(60);
});
