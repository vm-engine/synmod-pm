<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Sph\SphDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('builds letter, price table and terms', function () {
    $sph = ProjectSph::factory()->create(['issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true]);

    $data = SphDocumentData::build($sph);

    expect($data->number)->toStartWith('001/')->toContain('/SPH/')
        ->and($data->dateLabel)->toBe('7 Oktober 2026')
        ->and($data->recipientLines)->toBe(['PT Cahaya Arif Abadi', 'Jl. Tebet Barat X No. 33, Tebet', 'Jakarta Selatan 12810'])
        ->and($data->companyName)->toBe('PT VISION MEDIA CREATIVE')
        ->and($data->signerName)->toBe('MUHAMAD IHSAN FIRDAUS')
        ->and($data->signaturePath)->toEndWith('signatures/s.png')
        ->and($data->headerImagePath)->toEndWith('issuers/headers/h.png')
        ->and($data->footerImagePath)->toEndWith('issuers/footers/f.png')
        ->and($data->items[0]['number'])->toBe(1)
        ->and($data->items[0]['price'])->toBe('Rp15.000.000,-')
        ->and($data->items[0]['blocks'][1])->toBe(['level' => 2, 'marker' => '•', 'runs' => [['text' => 'login', 'bold' => false, 'italic' => false]]])
        ->and($data->items[1]['duration'])->toBe('-')
        ->and($data->grandTotalLabel)->toBe('Rp20.000.000,-')
        ->and($data->grandTotalWords)->toBe('DUA PULUH JUTA RUPIAH')
        ->and($data->paymentLines)->toBe([
            'Down Payment 30% dari nilai yang disepakati atau senilai Rp6.000.000,-',
            'Sisa pembayaran sebesar 70% dilakukan pada saat selesai pekerjaan.',
        ])
        ->and($data->timelineSummary)->toBe('Pelaksanaan Pekerjaan: 88 Hari Kerja.');
});

test('lampiran numbers follow timeplan then attachments', function () {
    Storage::disk('local')->put('projects/1/sph/attachments/a.png', sphPng(10, 10));
    $sph = ProjectSph::factory()->create(['attachments' => [
        ['path' => 'projects/1/sph/attachments/a.png', 'title' => 'Flowchart Proses Bisnis'],
        ['path' => 'projects/1/sph/attachments/missing.png', 'title' => 'Gone'],
    ]]);

    $data = SphDocumentData::build($sph);

    expect($data->timeplan['lampiran'])->toBe(2)
        ->and($data->timeplan['notes'][0])->toBe('Durasi pekerjaan: 88 hari kerja.')
        ->and($data->attachments)->toHaveCount(1)
        ->and($data->attachments[0]['lampiran'])->toBe(3)
        ->and($data->attachments[0]['title'])->toBe('Flowchart Proses Bisnis');
});

test('switched-off sections are empty and missing images are null', function () {
    $sph = ProjectSph::factory()->create([
        'payment_enabled' => false, 'show_timeline_summary' => false, 'show_timeplan' => false,
        'has_signature' => true, 'issuer_id' => sphIssuer(footer: false)->id,
    ]);

    $data = SphDocumentData::build($sph);

    expect($data->paymentLines)->toBe([])
        ->and($data->timelineSummary)->toBeNull()
        ->and($data->timeplan)->toBeNull()
        ->and($data->footerImagePath)->toBeNull()
        ->and($data->signaturePath)->toBeNull(); // factory signer has no stored file
});

test('percentages drop trailing zeros', function () {
    expect(SphDocumentData::percent(30.0))->toBe('30')
        ->and(SphDocumentData::percent(12.5))->toBe('12,5')
        ->and(SphDocumentData::percent(100.0))->toBe('100');
});
