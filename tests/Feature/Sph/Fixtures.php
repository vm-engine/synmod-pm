<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Signature;

/** PNG bytes of the given size (GD). */
function sphPng(int $width, int $height): string
{
    $image = imagecreatetruecolor($width, $height);
    imagefill($image, 0, 0, (int) imagecolorallocate($image, 0, 128, 255));
    ob_start();
    imagepng($image);

    return (string) ob_get_clean();
}

/** Issuer with a real header (and optional footer) on the faked public disk. */
function sphIssuer(bool $footer = true): DocumentIssuer
{
    Storage::disk('public')->put('issuers/headers/h.png', sphPng(800, 80));
    if ($footer) {
        Storage::disk('public')->put('issuers/footers/f.png', sphPng(800, 20));
    }

    return DocumentIssuer::factory()->create([
        'name' => 'PT VISION MEDIA CREATIVE',
        'header_image' => 'issuers/headers/h.png',
        'footer_image' => $footer ? 'issuers/footers/f.png' : null,
    ]);
}

function sphSigner(): Signature
{
    Storage::disk('public')->put('signatures/s.png', sphPng(300, 120));

    return Signature::factory()->create(['name' => 'MUHAMAD IHSAN FIRDAUS', 'position' => 'DIREKTUR', 'file' => 'signatures/s.png']);
}
