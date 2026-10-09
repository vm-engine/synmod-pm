<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Services\IssuerService;

beforeEach(function () {
    Storage::fake('public');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('create and update store the footer image and replace the old one', function () {
    $service = app(IssuerService::class);
    $data = ['name' => 'PT VMC', 'tax_id_name' => 'PT VMC', 'tax_id_address' => 'Bandung', 'tax_id_number' => '0959678293445000'];

    $issuer = $service->create($data, UploadedFile::fake()->image('header.png', 1200, 120), UploadedFile::fake()->image('footer.png', 1200, 40));
    $first = $issuer->footer_image;

    expect($first)->toStartWith('issuers/footers/')
        ->and(Storage::disk('public')->exists($first))->toBeTrue();

    $issuer = $service->update($issuer, $data, null, UploadedFile::fake()->image('footer2.png', 1200, 40));

    expect($issuer->footer_image)->not->toBe($first)
        ->and(Storage::disk('public')->exists($first))->toBeFalse()
        ->and(Storage::disk('public')->exists($issuer->footer_image))->toBeTrue();
});

test('delete removes the footer image', function () {
    $issuer = DocumentIssuer::factory()->create(['footer_image' => 'issuers/footers/x.png']);
    Storage::disk('public')->put('issuers/footers/x.png', 'x');

    app(IssuerService::class)->delete($issuer);

    expect(Storage::disk('public')->exists('issuers/footers/x.png'))->toBeFalse();
});
