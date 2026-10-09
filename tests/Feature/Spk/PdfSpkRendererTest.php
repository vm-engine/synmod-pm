<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Spk\PdfSpkRenderer;
use VmEngine\Pm\Services\Spk\SpkDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('renders a pdf', function () {
    $spk = ProjectSpk::factory()->create(['issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true]);

    expect(app(PdfSpkRenderer::class)->render(SpkDocumentData::build($spk)))->toStartWith('%PDF');
});

test('the html carries header, pasal, tables and signatures', function () {
    $html = view('pm::pdf.spk.document', ['data' => SpkDocumentData::build(ProjectSpk::factory()->create())])->render();

    expect($html)->toContain('PERJANJIAN KERJA SAMA')
        ->toContain('PENGEMBANGAN SISTEM INFORMASI PERUSAHAAN')
        ->toContain('PASAL 6')
        ->toContain('LAYANAN SEWA SERVER DAN DOMAIN')
        ->toContain('<strong>Customer Order</strong>')
        ->toContain('Nomor rekening')
        ->toContain('Monica Natayo')
        ->toContain('Demikian Perjanjian ini')
        ->toContain('Ronny Lesmana');
});
