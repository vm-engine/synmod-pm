<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

it('resets issuer and bumps the form nonce when creating a second invoice', function () {
    $issuer = DocumentIssuer::factory()->create();
    $project = Project::factory()->create();

    $component = Livewire::test('pm::project.invoice-form')
        ->call('createInvoice', $project->id)
        ->set('form.issuer_id', $issuer->id);

    $firstNonce = $component->get('formNonce');

    // Opening the form again for a fresh invoice must clear the stale value
    // and change the wire:key (nonce) that drives the adv-select re-mount.
    $component->call('createInvoice', $project->id);

    expect($component->get('form.issuer_id'))->toBeNull();
    expect($component->get('formNonce'))->toBeGreaterThan($firstNonce);
});
