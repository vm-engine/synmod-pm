<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\DocumentIssuer;

uses(RefreshDatabase::class);

test('document issuer has correct fillable fields', function () {
    $fillable = [
        'name',
        'header_image',
        'footer_image',
        'tax_id_name',
        'tax_id_address',
        'tax_id_number',
    ];

    expect((new DocumentIssuer)->getFillable())->toBe($fillable);
});

test('document issuer belongs to creator', function () {
    $UserModel = config('auth.providers.users.model');
    $creator = $UserModel::factory()->create();
    $issuer = DocumentIssuer::factory()->create(['created_by' => $creator->id]);

    expect($issuer->created_by)->toBe($creator->id);
    expect($issuer->creator)->toBeInstanceOf($UserModel);
    expect($issuer->creator->id)->toBe($creator->id);
});

test('document issuer belongs to updater', function () {
    $UserModel = config('auth.providers.users.model');
    $updater = $UserModel::factory()->create();
    $issuer = DocumentIssuer::factory()->create(['updated_by' => $updater->id]);

    expect($issuer->updated_by)->toBe($updater->id);
    expect($issuer->updater)->toBeInstanceOf($UserModel);
    expect($issuer->updater->id)->toBe($updater->id);
});

test('header image url accessor returns storage url', function () {
    $path = 'issuers/headers/test.jpg';
    $issuer = DocumentIssuer::factory()->create(['header_image' => $path]);

    expect($issuer->header_image_url)->toBe(Storage::url($path));
});

test('header image url accessor returns storage url when image exists', function () {
    Storage::fake('public');
    $path = 'issuers/headers/test.jpg';

    $issuer = DocumentIssuer::factory()->create(['header_image' => $path]);

    expect($issuer->header_image_url)->toBe(Storage::url($path));
});

test('delete token can be generated and validated', function () {
    $issuer = DocumentIssuer::factory()->create();
    $token = $issuer->generateDeleteToken($issuer->id);

    expect($token)->toBeString();
    expect(DocumentIssuer::validateDeleteToken($token))->toBe((string) $issuer->id);
});

test('delete token validation fails for invalid token', function () {
    expect(DocumentIssuer::validateDeleteToken('invalid-token'))->toBeFalse();
});

test('delete token validation fails when session is missing', function () {
    $issuer = DocumentIssuer::factory()->create();

    // Clear session
    session()->forget('dToken');

    expect(DocumentIssuer::validateDeleteToken('some-token'))->toBeFalse();
});

test('can create document issuer with all fields', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Test Company',
        'header_image' => 'test/path.jpg',
        'tax_id_name' => 'Test Company Legal Name',
        'tax_id_address' => '123 Test Street',
        'tax_id_number' => '1234567890123456',
    ]);

    expect($issuer->name)->toBe('Test Company');
    expect($issuer->header_image)->toBe('test/path.jpg');
    expect($issuer->tax_id_name)->toBe('Test Company Legal Name');
    expect($issuer->tax_id_address)->toBe('123 Test Street');
    expect($issuer->tax_id_number)->toBe('1234567890123456');
    expect($issuer->created_by)->toBeInt();
});

test('can update document issuer', function () {
    $issuer = DocumentIssuer::factory()->create([
        'name' => 'Old Name',
    ]);

    $issuer->update(['name' => 'New Name']);

    expect($issuer->fresh()->name)->toBe('New Name');
});

test('can delete document issuer', function () {
    $issuer = DocumentIssuer::factory()->create();
    $id = $issuer->id;

    $issuer->delete();

    expect(DocumentIssuer::find($id))->toBeNull();
});

test('factory creates valid document issuer', function () {
    $issuer = DocumentIssuer::factory()->create();

    expect($issuer->name)->toBeString();
    expect($issuer->header_image)->toBeString();
    expect($issuer->tax_id_name)->toBeString();
    expect($issuer->tax_id_address)->toBeString();
    expect($issuer->tax_id_number)->toBeString();
    expect($issuer->created_by)->toBeInt();
});

test('factory withUpdater state sets updater', function () {
    $issuer = DocumentIssuer::factory()->withUpdater()->create();

    expect($issuer->updated_by)->not->toBeNull();
    expect($issuer->updater)->not->toBeNull();
});
