<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Sph\PdfSphRenderer;
use VmEngine\Pm\Services\Sph\SphDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('renders a pdf', function () {
    $sph = ProjectSph::factory()->create(['issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true]);

    expect(app(PdfSphRenderer::class)->render(SphDocumentData::build($sph)))->toStartWith('%PDF');
});

test('the html carries the letter, table, terms and timeplan', function () {
    $sph = ProjectSph::factory()->create();

    $html = view('pm::pdf.sph.document', ['data' => SphDocumentData::build($sph), 'ganttImages' => ['data:image/svg+xml;base64,AA==']])->render();

    expect($html)->toContain('SURAT PENAWARAN HARGA')
        ->toContain('Pembuatan Log Aplikasi')
        ->toContain('Durasi Pekerjaan')
        ->toContain('DUA PULUH JUTA RUPIAH')
        ->toContain('Metode Pembayaran')
        ->toContain('Pelaksanaan Pekerjaan: ')
        ->toContain('TIMEPLAN')
        ->toContain('<strong>activity_logs</strong>');
});
