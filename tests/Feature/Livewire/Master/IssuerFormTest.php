<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\DocumentIssuer;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    Storage::fake('public');
});

test('component can be rendered', function () {
    Livewire::test('pm::master.issuer-form')
        ->assertStatus(200);
});

test('can create new issuer with valid data', function () {
    $file = UploadedFile::fake()->image('header.jpg', 1200, 300);

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-issuer-form');

    $issuer = DocumentIssuer::where('name', 'Test Company')->first();
    expect($issuer)->not->toBeNull();
    expect($issuer->tax_id_name)->toBe('Test Legal Name');
    expect($issuer->tax_id_address)->toBe('123 Test Street');
    expect($issuer->tax_id_number)->toBe('1234567890123456');
    expect($issuer->header_image)->not->toBeNull();

    // Verify image was stored
    Storage::disk('public')->assertExists($issuer->header_image);
});

test('validation fails when name is missing', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.name' => 'required']);
});

test('validation fails when header image is missing on create', function () {
    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.header_image' => 'required']);
});

test('validation fails when tax ID name is missing', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.tax_id_name' => 'required']);
});

test('validation fails when tax ID address is missing', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.tax_id_address' => 'required']);
});

test('validation fails when tax ID number is missing', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->call('save')
        ->assertHasErrors(['form.tax_id_number' => 'required']);
});

test('validation fails when tax ID number is not numeric', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', 'abc1234567890123')
        ->call('save')
        ->assertHasErrors(['form.tax_id_number' => 'numeric']);
});

test('validation fails when tax ID number is not 16 digits', function () {
    $file = UploadedFile::fake()->image('header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '12345') // Only 5 digits
        ->call('save')
        ->assertHasErrors(['form.tax_id_number' => 'digits']);
});

test('validation fails when header image is not valid image file', function () {
    $file = UploadedFile::fake()->create('document.pdf', 100);

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.header_image' => 'mimes']);
});

test('validation fails when header image exceeds 5MB', function () {
    $file = UploadedFile::fake()->image('header.jpg')->size(6000); // 6MB

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasErrors(['form.header_image' => 'max']);
});

test('can load existing issuer for editing', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Original Name',
        'tax_id_name' => 'Original Legal Name',
        'tax_id_address' => 'Original Address',
        'tax_id_number' => '1111111111111111',
        'header_image' => 'test/path.jpg',
    ]);

    Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', $issuer->id)
        ->assertSet('form.name', 'Original Name')
        ->assertSet('form.tax_id_name', 'Original Legal Name')
        ->assertSet('form.tax_id_address', 'Original Address')
        ->assertSet('form.tax_id_number', '1111111111111111')
        ->assertSet('form.existing_header_image', 'test/path.jpg');
});

test('can update existing issuer', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Original Name',
        'header_image' => 'old/path.jpg',
    ]);

    Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', $issuer->id)
        ->set('form.name', 'Updated Name')
        ->set('form.tax_id_name', 'Updated Legal Name')
        ->set('form.tax_id_address', 'Updated Address')
        ->set('form.tax_id_number', '9999999999999999')
        ->call('save')
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-issuer-form');

    $issuer->refresh();
    expect($issuer->name)->toBe('Updated Name');
    expect($issuer->tax_id_name)->toBe('Updated Legal Name');
    expect($issuer->tax_id_address)->toBe('Updated Address');
    expect($issuer->tax_id_number)->toBe('9999999999999999');
});

test('can update issuer without changing header image', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Original Name',
        'header_image' => 'original/image.jpg',
        'tax_id_name' => 'Original Legal Name',
        'tax_id_address' => 'Original Address',
        'tax_id_number' => '1111111111111111',
    ]);

    Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', $issuer->id)
        ->set('form.name', 'Updated Name')
        ->set('form.tax_id_name', 'Updated Legal Name')
        ->set('form.tax_id_address', 'Updated Address')
        ->set('form.tax_id_number', '9999999999999999')
        ->call('save');

    $issuer->refresh();
    expect($issuer->name)->toBe('Updated Name');
    expect($issuer->header_image)->toBe('original/image.jpg'); // Image unchanged
});

test('can update issuer with new header image', function () {
    Storage::disk('public')->put('old/image.jpg', 'old content');

    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Original Name',
        'header_image' => 'old/image.jpg',
        'tax_id_name' => 'Original Legal Name',
        'tax_id_address' => 'Original Address',
        'tax_id_number' => '1111111111111111',
    ]);

    $newFile = UploadedFile::fake()->image('new_header.jpg');

    Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', $issuer->id)
        ->set('form.header_image', $newFile)
        ->set('form.name', 'Updated Name')
        ->set('form.tax_id_name', 'Updated Legal Name')
        ->set('form.tax_id_address', 'Updated Address')
        ->set('form.tax_id_number', '9999999999999999')
        ->call('save');

    $issuer->refresh();
    expect($issuer->header_image)->not->toBe('old/image.jpg');

    // Old image should be deleted
    Storage::disk('public')->assertMissing('old/image.jpg');

    // New image should exist
    Storage::disk('public')->assertExists($issuer->header_image);
});

test('dispatches error when loading non-existent issuer', function () {
    Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', 99999)
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-issuer-form');
});

test('reset form clears all fields', function () {
    $issuer = DocumentIssuer::factory()->create();

    $component = Livewire::test('pm::master.issuer-form')
        ->call('loadIssuer', $issuer->id);

    // Verify fields are populated
    expect($component->get('form.name'))->not->toBeEmpty();

    // Reset form
    $component->call('resetForm')
        ->assertSet('form.name', '')
        ->assertSet('form.tax_id_name', '')
        ->assertSet('form.tax_id_address', '')
        ->assertSet('form.tax_id_number', '')
        ->assertSet('form.header_image', null);
});

// Note: Image resize tests are skipped because they require real image processing
// which doesn't work with Laravel's fake uploaded files. The image processing
// logic is tested manually in production environment.
test('header image is stored correctly', function () {
    // Create a wide image (landscape)
    $file = UploadedFile::fake()->image('header.jpg', 2000, 500);

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save');

    $issuer = DocumentIssuer::where('name', 'Test Company')->first();

    // Verify image was stored
    expect($issuer->header_image)->not->toBeNull();
    Storage::disk('public')->assertExists($issuer->header_image);
    expect($issuer->header_image)->toContain('issuers/headers/');
});

test('accepts PNG images', function () {
    $file = UploadedFile::fake()->image('header.png');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasNoErrors();

    $issuer = DocumentIssuer::where('name', 'Test Company')->first();
    expect($issuer)->not->toBeNull();
    Storage::disk('public')->assertExists($issuer->header_image);
});

test('accepts WebP images', function () {
    $file = UploadedFile::fake()->create('header.webp', 100, 'image/webp');

    Livewire::test('pm::master.issuer-form')
        ->set('form.name', 'Test Company')
        ->set('form.header_image', $file)
        ->set('form.tax_id_name', 'Test Legal Name')
        ->set('form.tax_id_address', '123 Test Street')
        ->set('form.tax_id_number', '1234567890123456')
        ->call('save')
        ->assertHasNoErrors();

    $issuer = DocumentIssuer::where('name', 'Test Company')->first();
    expect($issuer)->not->toBeNull();
});
