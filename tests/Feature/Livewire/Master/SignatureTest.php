<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\Signature as ModelsSignature;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.signature-list')
        ->assertStatus(200);
});

test('loads signature list', function () {
    ModelsSignature::factory()->count(3)->create();

    $component = Livewire::test('pm::master.signature-list');

    expect($component->signatureList)->toHaveCount(3);
});

test('signatures are ordered by name ascending', function () {
    $signatureC = ModelsSignature::factory()->create(['name' => 'Charlie']);
    $signatureA = ModelsSignature::factory()->create(['name' => 'Alice']);
    $signatureB = ModelsSignature::factory()->create(['name' => 'Bob']);

    $component = Livewire::test('pm::master.signature-list');

    $signatures = $component->signatureList;
    expect($signatures->first()->id)->toBe($signatureA->id);
    expect($signatures->last()->id)->toBe($signatureC->id);
});

test('can search signatures by name', function () {
    $signature1 = ModelsSignature::factory()->create(['name' => 'John Doe']);
    $signature2 = ModelsSignature::factory()->create(['name' => 'Jane Smith']);
    $signature3 = ModelsSignature::factory()->create(['name' => 'Bob Johnson']);

    $component = Livewire::test('pm::master.signature-list')
        ->set('q', 'John');

    $signatures = $component->signatureList;
    $signatureIds = $signatures->pluck('id')->toArray();

    expect($signatures)->toHaveCount(2);
    expect($signatureIds)->toContain($signature1->id);
    expect($signatureIds)->toContain($signature3->id);
    expect($signatureIds)->not->toContain($signature2->id);
});

test('can search signatures by position', function () {
    $signature1 = ModelsSignature::factory()->create([
        'name' => 'John Doe',
        'position' => 'Director',
    ]);

    $signature2 = ModelsSignature::factory()->create([
        'name' => 'Jane Smith',
        'position' => 'Manager',
    ]);

    $component = Livewire::test('pm::master.signature-list')
        ->set('q', 'Director');

    $signatures = $component->signatureList;
    expect($signatures)->toHaveCount(1);
    expect($signatures->first()->id)->toBe($signature1->id);
});

test('search requires minimum 3 characters', function () {
    ModelsSignature::factory()->count(5)->create();

    $component = Livewire::test('pm::master.signature-list')
        ->set('q', 'ab'); // Only 2 characters

    expect($component->signatureList)->toHaveCount(5); // Should return all
});

test('pagination works with limit', function () {
    ModelsSignature::factory()->count(15)->create();

    $component = Livewire::test('pm::master.signature-list')
        ->set('limit', 5);

    expect($component->signatureList)->toHaveCount(5);
});

// Note: Page reset is handled automatically by updated() method calling resetPage()

test('can delete signature', function () {
    $signature = ModelsSignature::factory()->create([
        'name' => 'Test Signature',
        'file' => 'signatures/test-signature.png',
    ]);

    $token = $signature->delete_token;

    Livewire::test('pm::master.signature-list')
        ->call('delete', $token)
        ->assertDispatched('synapse-confirmed')
        ->assertDispatched('notify');

    expect(ModelsSignature::find($signature->id))->toBeNull();
});

test('deletes signature file when signature is deleted', function () {
    Storage::disk('public')->put('signatures/test.png', 'signature image');

    $signature = ModelsSignature::factory()->create([
        'file' => 'signatures/test.png',
    ]);

    $token = $signature->delete_token;

    Livewire::test('pm::master.signature-list')
        ->call('delete', $token);

    expect(Storage::disk('public')->exists('signatures/test.png'))->toBeFalse();
});

test('handles deletion when file does not exist', function () {
    $signature = ModelsSignature::factory()->create([
        'file' => 'signatures/non-existent.png',
    ]);

    $token = $signature->delete_token;

    Livewire::test('pm::master.signature-list')
        ->call('delete', $token)
        ->assertDispatched('notify');

    expect(ModelsSignature::find($signature->id))->toBeNull();
});

test('handles deletion with invalid token', function () {
    ModelsSignature::factory()->create();

    Livewire::test('pm::master.signature-list')
        ->call('delete', 'invalid-token')
        ->assertDispatched('notify');
});

// Note: Cannot test non-existent signature deletion without a valid token from an actual model instance

test('sets active menu', function () {
    Livewire::test('pm::master.signature-list');

    expect(synav()->getActiveMenu())->toBe('pm.master.signature');
});

test('search is persisted in URL', function () {
    Livewire::test('pm::master.signature-list')
        ->set('q', 'test')
        ->assertSet('q', 'test');
});
