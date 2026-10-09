<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Spk\Docx\DocxSpkRenderer;
use VmEngine\Pm\Services\Spk\SpkDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
    $this->target = tempnam(sys_get_temp_dir(), 'spktest').'.docx';
});

afterEach(function () {
    @unlink($this->target);
});

function spkDocxPart(string $file, string $part): string
{
    $zip = new ZipArchive;
    $zip->open($file);
    $content = (string) $zip->getFromName($part);
    $zip->close();

    return $content;
}

test('renders a well-formed document with the SPK content', function () {
    Storage::disk('local')->put('projects/1/spk/attachments/a.png', sphPng(1600, 900));
    $spk = ProjectSpk::factory()->create([
        'issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true,
        'attachments' => [['path' => 'projects/1/spk/attachments/a.png', 'title' => 'Struktur']],
    ]);

    app(DocxSpkRenderer::class)->render(SpkDocumentData::build($spk), $this->target);
    $xml = spkDocxPart($this->target, 'word/document.xml');

    expect((new DOMDocument)->loadXML($xml))->toBeTrue()
        ->and($xml)->toContain('PERJANJIAN KERJA SAMA')
        ->and($xml)->toContain('PASAL 10')
        ->and($xml)->toContain('Rabu tanggal Empat')
        ->and($xml)->toContain('Nomor rekening')
        ->and($xml)->toContain('Monica Natayo')
        ->and($xml)->toContain('STRUKTUR')
        ->and(substr_count($xml, '<w:drawing>'))->toBe(2) // signature + attachment
        ->and(spkDocxPart($this->target, 'word/_rels/header1.xml.rels'))->toContain('letterhead-header1');
});

test('without images the document is still valid', function () {
    app(DocxSpkRenderer::class)->render(SpkDocumentData::build(ProjectSpk::factory()->create()), $this->target);

    expect((new DOMDocument)->loadXML(spkDocxPart($this->target, 'word/document.xml')))->toBeTrue();
});
