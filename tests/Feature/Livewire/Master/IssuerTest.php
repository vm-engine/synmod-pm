<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\DocumentIssuer;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.issuer-list')
        ->assertStatus(200);
});

test('displays issuer list', function () {
    DocumentIssuer::factory()->create(['name' => 'Company A']);
    DocumentIssuer::factory()->create(['name' => 'Company B']);

    Livewire::test('pm::master.issuer-list')
        ->assertSee('Company A')
        ->assertSee('Company B');
});

test('displays no data message when empty', function () {
    Livewire::test('pm::master.issuer-list')
        ->assertSee(__('pm::issuer.no_data'));
});

test('can search by name', function () {
    DocumentIssuer::factory()->create(['name' => 'Acme Corporation']);
    DocumentIssuer::factory()->create(['name' => 'Tech Solutions']);

    Livewire::test('pm::master.issuer-list')
        ->set('q', 'Acme')
        ->assertSee('Acme Corporation')
        ->assertDontSee('Tech Solutions');
});

test('can search by tax ID number', function () {
    DocumentIssuer::factory()->create([
        'name' => 'Company A',
        'tax_id_number' => '1111111111111111',
    ]);
    DocumentIssuer::factory()->create([
        'name' => 'Company B',
        'tax_id_number' => '2222222222222222',
    ]);

    Livewire::test('pm::master.issuer-list')
        ->set('q', '1111')
        ->assertSee('Company A')
        ->assertDontSee('Company B');
});

test('can search by tax ID name', function () {
    DocumentIssuer::factory()->create([
        'name' => 'Company A',
        'tax_id_name' => 'Legal Name A',
    ]);
    DocumentIssuer::factory()->create([
        'name' => 'Company B',
        'tax_id_name' => 'Legal Name B',
    ]);

    Livewire::test('pm::master.issuer-list')
        ->set('q', 'Legal Name A')
        ->assertSee('Company A')
        ->assertDontSee('Company B');
});

test('search requires minimum 3 characters', function () {
    DocumentIssuer::factory()->create(['name' => 'Acme Corporation']);

    // Search with 2 characters should show all
    $component = Livewire::test('pm::master.issuer-list')
        ->set('q', 'Ac');

    expect($component->get('issuerList')->count())->toBe(1);

    // Search with 3 characters should filter
    $component->set('q', 'Acm')
        ->assertSee('Acme Corporation');
});

test('pagination works correctly', function () {
    DocumentIssuer::factory()->count(15)->create();

    $component = Livewire::test('pm::master.issuer-list')
        ->set('limit', 12);

    expect($component->get('issuerList')->count())->toBe(12);
});

test('can change pagination limit', function () {
    DocumentIssuer::factory()->count(10)->create();

    Livewire::test('pm::master.issuer-list')
        ->set('limit', 6)
        ->assertSet('limit', 6);
});

test('can delete issuer with valid token', function () {
    Storage::fake('public');

    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Test Issuer',
        'header_image' => 'issuers/headers/test.jpg',
    ]);

    // Create the image file
    Storage::disk('public')->put('issuers/headers/test.jpg', 'fake content');

    $token = $issuer->delete_token;

    Livewire::test('pm::master.issuer-list')
        ->call('delete', $token)
        ->assertDispatched('notify');

    expect(DocumentIssuer::find($issuer->id))->toBeNull();
    Storage::disk('public')->assertMissing('issuers/headers/test.jpg');
});

test('delete dispatches error for invalid token', function () {
    Livewire::test('pm::master.issuer-list')
        ->call('delete', 'invalid-token')
        ->assertDispatched('notify');
});

// Note: Cannot test non-existent issuer deletion without a valid token from an actual model instance

test('reset page on search', function () {
    DocumentIssuer::factory()->count(20)->create();

    $component = Livewire::test('pm::master.issuer-list')
        ->set('limit', 10);

    // Go to page 2
    $component->call('gotoPage', 2);

    // Search should reset to page 1
    $component->set('q', 'test');

    expect($component->get('paginators')['page'])->toBe(1);
});

test('displays header image when available', function () {
    Storage::fake('public');
    $path = 'issuers/headers/test.jpg';
    Storage::disk('public')->put($path, 'fake content');

    DocumentIssuer::factory()->create([
        'name' => 'Test Company',
        'header_image' => $path,
    ]);

    Livewire::test('pm::master.issuer-list')
        ->assertSee(Storage::url($path));
});

test('displays issuer information in cards', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Test Company',
        'tax_id_name' => 'Test Legal Name',
        'tax_id_number' => '1234567890123456',
        'tax_id_address' => '123 Test Street, Test City',
    ]);

    Livewire::test('pm::master.issuer-list')
        ->assertSee('Test Company')
        ->assertSee('Test Legal Name')
        ->assertSee('1234567890123456')
        ->assertSee('123 Test Street');
});

test('truncates long addresses', function () {
    $longAddress = str_repeat('This is a very long address. ', 10); // > 80 chars

    DocumentIssuer::factory()->create([
        'name' => 'Test Company',
        'tax_id_address' => $longAddress,
    ]);

    $component = Livewire::test('pm::master.issuer-list');

    // Should see truncated version (first 80 chars)
    expect($component->html())->toContain(substr($longAddress, 0, 77)); // Str::limit adds ...
});

test('search is case insensitive', function () {
    DocumentIssuer::factory()->create(['name' => 'Acme Corporation']);

    Livewire::test('pm::master.issuer-list')
        ->set('q', 'acme')
        ->assertSee('Acme Corporation');

    Livewire::test('pm::master.issuer-list')
        ->set('q', 'ACME')
        ->assertSee('Acme Corporation');

    Livewire::test('pm::master.issuer-list')
        ->set('q', 'AcMe')
        ->assertSee('Acme Corporation');
});
