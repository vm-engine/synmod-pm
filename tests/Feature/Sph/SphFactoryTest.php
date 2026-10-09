<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\Sph\SphFactory;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('a project without a proposal gets a draft prefilled from client and project', function () {
    $client = Client::factory()->create(['company' => 'PT Anugerah Berkat Sarana', 'pic' => 'Bapak Gustut Rudy', 'address' => 'Jl. Mawar 1', 'city' => 'Bandung']);
    $project = Project::factory()->create(['client_id' => $client->id, 'client_pic' => null, 'name' => 'Aplikasi ERP Alat Kesehatan', 'nominal' => 100000000, 'down_payment' => 40000000]);
    $issuer = DocumentIssuer::factory()->create();
    $signature = Signature::factory()->create();

    $sph = app(SphFactory::class)->create($project);

    expect($sph->status)->toBe(ProposalStatus::Draft)
        ->and($sph->recipient_name)->toBe('Bapak Gustut Rudy')
        ->and($sph->recipient_company)->toBe('PT Anugerah Berkat Sarana')
        ->and($sph->recipient_address)->toBe("Jl. Mawar 1\nBandung")
        ->and($sph->need)->toBe('Aplikasi ERP Alat Kesehatan')
        ->and($sph->issuer_id)->toBe($issuer->id)
        ->and($sph->signature_id)->toBe($signature->id)
        ->and((float) $sph->dp_percentage)->toBe(40.0)
        ->and($sph->items)->toHaveCount(1)
        ->and($sph->show_timeplan)->toBeFalse();
});

test('recipient falls back to the generic addressee and dp to 30%', function () {
    $client = Client::factory()->create(['pic' => '']);
    $project = Project::factory()->create(['client_id' => $client->id, 'client_pic' => null, 'down_payment' => null]);

    $sph = app(SphFactory::class)->create($project);

    expect($sph->recipient_name)->toBe('Bapak/Ibu Pimpinan')
        ->and((float) $sph->dp_percentage)->toBe(30.0);
});

test('a draft proposal is imported: costing becomes items, timeline is copied', function () {
    $proposal = ProjectProposal::factory()->create();

    $sph = app(SphFactory::class)->create($proposal->project);

    expect($sph->items)->toHaveCount(2)
        ->and($sph->items[0]['name'])->toBe($proposal->costing[0]['section'])
        ->and($sph->items[0]['description'])->toBe(implode("\n", array_map(fn (string $f): string => '- '.$f, $proposal->costing[0]['features'])))
        ->and((float) $sph->items[1]['price'])->toBe((float) $proposal->costing[1]['price'])
        ->and($sph->timeline['tasks'])->toBe($proposal->timeline['tasks'])
        ->and($sph->show_timeplan)->toBeTrue();
});

test('a published proposal is imported from its snapshot, not the draft edits', function () {
    $proposal = ProjectProposal::factory()->create();
    $snapshot = $proposal->only(ProjectProposal::CONTENT_FIELDS);
    $proposal->forceFill(['status' => ProposalStatus::Published, 'published_snapshot' => $snapshot])->save();
    $proposal->update(['costing' => [['id' => 'x', 'section' => 'Unpublished edit', 'features' => [], 'price' => 1]]]);

    $sph = app(SphFactory::class)->create($proposal->project);

    expect($sph->items[0]['name'])->toBe($snapshot['costing'][0]['section']);
});

test('issuer and signer default to the last published SPH', function () {
    // Lower ids than the previous SPH's, so "first by id" would pick these instead
    DocumentIssuer::factory()->create();
    Signature::factory()->create();
    $previous = ProjectSph::factory()->create(['published_at' => now()]);

    $sph = app(SphFactory::class)->create(Project::factory()->create());

    expect($sph->issuer_id)->toBe($previous->issuer_id)
        ->and($sph->signature_id)->toBe($previous->signature_id);
});
