<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Sph\Docx\DocxSphRenderer;
use VmEngine\Pm\Services\Sph\SphDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
    $this->target = tempnam(sys_get_temp_dir(), 'sphtest').'.docx';
});

afterEach(function () {
    @unlink($this->target);
});

function docxPart(string $file, string $part): string
{
    $zip = new ZipArchive;
    $zip->open($file);
    $content = (string) $zip->getFromName($part);
    $zip->close();

    return $content;
}

test('renders a well-formed document with the SPH content', function () {
    Storage::disk('local')->put('projects/1/sph/attachments/a.png', sphPng(1600, 900));
    $sph = ProjectSph::factory()->create([
        'issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true,
        'attachments' => [['path' => 'projects/1/sph/attachments/a.png', 'title' => 'Flowchart']],
    ]);

    app(DocxSphRenderer::class)->render(SphDocumentData::build($sph), $this->target);
    $xml = docxPart($this->target, 'word/document.xml');
    $dom = new DOMDocument;

    expect($dom->loadXML($xml))->toBeTrue()
        ->and($xml)->toContain('SURAT PENAWARAN HARGA')
        ->and($xml)->toContain('PT Cahaya Arif Abadi')
        ->and($xml)->toContain('Pembuatan Log Aplikasi')
        ->and($xml)->toContain('activity_logs')
        ->and($xml)->toContain('Durasi Pekerjaan')
        ->and($xml)->toContain('DUA PULUH JUTA RUPIAH')
        ->and($xml)->toContain('Sisa pembayaran sebesar 70%')
        ->and($xml)->toContain('TIMEPLAN')
        ->and($xml)->toContain('FLOWCHART')
        ->and($xml)->toContain('svgBlip')
        ->and(substr_count($xml, '<w:drawing>'))->toBe(3); // signature + 1 gantt page + attachment

    $rels = docxPart($this->target, 'word/_rels/document.xml.rels');
    expect(substr_count($rels, 'rIdSph'))->toBe(4) // signature, gantt png + svg, attachment
        ->and(docxPart($this->target, '[Content_Types].xml'))->toContain('Extension="svg"')
        ->and(docxPart($this->target, 'word/_rels/header1.xml.rels'))->toContain('media/letterhead-header1.png')
        ->and(docxPart($this->target, 'word/_rels/footer1.xml.rels'))->toContain('media/letterhead-footer1.png');
});

test('hidden sections are left out and a missing footer empties the footer part', function () {
    $sph = ProjectSph::factory()->create([
        'issuer_id' => sphIssuer(footer: false)->id,
        'show_duration' => false, 'payment_enabled' => false, 'show_timeline_summary' => false, 'show_timeplan' => false, 'tax_note' => false,
    ]);

    app(DocxSphRenderer::class)->render(SphDocumentData::build($sph), $this->target);
    $xml = docxPart($this->target, 'word/document.xml');

    expect($xml)->not->toContain('Durasi Pekerjaan')
        ->and($xml)->not->toContain('Metode Pembayaran')
        ->and($xml)->not->toContain('TIMEPLAN')
        ->and($xml)->not->toContain('belum termasuk pajak')
        ->and(docxPart($this->target, 'word/footer1.xml'))->not->toContain('<w:drawing>');
});

test('webp images are converted to png', function () {
    $image = imagecreatetruecolor(20, 10);
    ob_start();
    imagewebp($image);
    $webp = (string) ob_get_clean();

    [$bytes, $extension, $width, $height] = DocxSphRenderer::image($webp);

    expect($extension)->toBe('png')
        ->and(getimagesizefromstring($bytes)['mime'])->toBe('image/png')
        ->and([$width, $height])->toBe([20, 10]);
});
