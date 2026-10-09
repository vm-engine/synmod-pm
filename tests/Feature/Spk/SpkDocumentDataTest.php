<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Spk\SpkDocumentData;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('pasal numbers skip disabled optional pasal and put extra pasal before Lain-lain', function () {
    expect(SpkDocumentData::pasalNumbers(true, true, 0))->toBe([
        'asas' => 1, 'pokok' => 2, 'duration' => 3, 'obligations' => 4, 'payment' => 5, 'server' => 6,
        'confidentiality' => 7, 'dispute' => 8, 'correspondence' => 9, 'misc' => 10,
    ])->and(SpkDocumentData::pasalNumbers(false, false, 2))->toBe([
        'asas' => 1, 'pokok' => 2, 'duration' => 3, 'obligations' => 4, 'payment' => 5,
        'confidentiality' => 6, 'dispute' => 7, 'extra.0' => 8, 'extra.1' => 9, 'misc' => 10,
    ]);
});

test('date and quantities in words', function () {
    expect(SpkDocumentData::dateInWords(CarbonImmutable::parse('2026-02-04')))
        ->toBe('Rabu tanggal Empat bulan Februari tahun Dua Ribu Dua Puluh Enam (04-02-2026)')
        ->and(SpkDocumentData::quantity(88))->toBe('88 (Delapan Puluh Delapan)');
});

test('free text splits into ayat at top-level paragraphs and list items', function () {
    $items = SpkDocumentData::splitAyat("First.\n\n1. Second:\n   1. nested\n2. Third");

    expect($items)->toHaveCount(3)
        ->and(spkText($items[1][0]))->toBe('Second:')
        ->and($items[1][1]['level'])->toBe(2)
        ->and(SpkDocumentData::splitAyat(''))->toBe([]);
});

test('installment text', function () {
    expect(SpkDocumentData::installmentText(['label' => 'Pembayaran awal (down payment)', 'type' => 'percent', 'value' => 30, 'count' => 1, 'due' => 'dibayar pada saat Perjanjian ini ditandatangani.'], 56000000))
        ->toBe('Pembayaran awal (down payment) 30% atau sebesar Rp16.800.000,- (Enam Belas Juta Delapan Ratus Ribu Rupiah) dibayar pada saat Perjanjian ini ditandatangani.')
        ->and(SpkDocumentData::installmentText(['label' => 'Cicilan', 'type' => 'amount', 'value' => 13000000, 'count' => 11, 'due' => ''], 0))
        ->toBe('Cicilan sebesar Rp13.000.000,- (Tiga Belas Juta Rupiah) sebanyak 11 (Sebelas) kali.');
});

test('builds header, parties and all pasal of the ABS SPK', function () {
    $spk = ProjectSpk::factory()->create(['issuer_id' => sphIssuer()->id, 'signature_id' => sphSigner()->id, 'has_signature' => true]);

    $data = SpkDocumentData::build($spk);

    expect($data->number)->toStartWith('001/')->toContain('/SPK/')
        ->and($data->dateLabel)->toBe('04 Februari 2026')
        ->and($data->title)->toBe('PENGEMBANGAN SISTEM INFORMASI PERUSAHAAN')
        ->and($data->opening)->toContain('pada hari ini, Rabu tanggal Empat bulan Februari')
        ->and(spkText($data->parties[0]))->toStartWith('PT ANUGERAH BERKAT SARANA, berkedudukan di Jl. H. Adam Malik Ruko Graha 99 No.6, Kreo Selatan, Kota Tangerang')
        ->and(spkText($data->parties[0]))->toEndWith('selaku Direktur dan karenanya sah bertindak untuk dan atas nama PT Anugerah Berkat Sarana, selanjutnya disebut PIHAK PERTAMA.')
        ->and(spkText($data->parties[1]))->toStartWith('PT VISION MEDIA CREATIVE, berkedudukan di Bandung')
        ->and(spkText($data->recitals[0]))->toBe('Bahwa PIHAK PERTAMA adalah perusahaan distributor alat kesehatan yang berfokus pada produk intervensi kardiologi.')
        ->and(array_column($data->pasal, 'title'))->toBe([
            'ASAS KERJA SAMA', 'POKOK PERJANJIAN', 'JANGKA WAKTU', 'HAK DAN KEWAJIBAN', 'NILAI KONTRAK DAN CARA PEMBAYARAN',
            'LAYANAN SEWA SERVER DAN DOMAIN', 'KERAHASIAAN', 'PENYELESAIAN PERSELISIHAN', 'KORESPONDENSI', 'LAIN-LAIN',
        ])
        ->and(array_column($data->pasal, 'number'))->toBe(range(1, 10))
        ->and($data->secondSigner)->toBe(['label' => 'PIHAK KEDUA', 'company' => 'PT VISION MEDIA CREATIVE', 'name' => 'MUHAMAD IHSAN FIRDAUS', 'position' => 'DIREKTUR'])
        ->and($data->signaturePath)->toEndWith('signatures/s.png');

    $pokok = spkPasalText($data->pasal[1]);
    expect($pokok)->toContain('1. PIHAK PERTAMA dengan ini menunjuk PIHAK KEDUA. Dan PIHAK KEDUA dengan ini menerima penunjukan PIHAK PERTAMA untuk melakukan pekerjaan berupa Pengembangan Sistem Informasi Perusahaan:')
        ->toContain('• Master Data : Warehouse, Product, Customer')
        ->toContain('3. Spesifikasi lengkap mengenai cakupan pekerjaan termuat pada dokumen:')
        ->toContain('No. 001/PRJ/VMC/534-NC/I/2026 yang merupakan satu kesatuan');

    $duration = spkPasalText($data->pasal[2]);
    expect($duration)->toContain('1. Jangka waktu pelaksanaan Pengembangan Sistem Informasi Perusahaan adalah 88 (Delapan Puluh Delapan) hari kerja, terhitung dimulai sejak')
        ->toContain('2. Jangka waktu maintenance & supporting adalah 8 (Delapan) bulan kalender');

    $payment = spkPasalText($data->pasal[4]);
    expect($payment)->toContain('1. Fee dan Biaya yang harus dibayarkan oleh PIHAK PERTAMA kepada PIHAK KEDUA adalah Rp178.000.000,- (Seratus Tujuh Puluh Delapan Juta Rupiah). Harga belum termasuk Pajak.')
        ->toContain('sebagaimana dimaksud dalam Pasal 2 Pokok Perjanjian')
        ->toContain('a. Pembayaran awal (down payment) sebesar Rp35.000.000,- (Tiga Puluh Lima Juta Rupiah) dibayar pada saat Perjanjian ini ditandatangani.')
        ->toContain('sebanyak 11 (Sebelas) kali terhitung sejak bulan Maret 2026.')
        ->toContain('4. Pelaksanaan pembayaran biaya')
        ->toContain('Nomor rekening: ');

    $correspondence = spkPasalText($data->pasal[8]);
    expect($correspondence)->toContain('Nama: Monica Natayo')
        ->toContain('2. Surat-menyurat ke alamat tersebut')
        ->toContain('3. Dalam hal terjadi perubahan alamat');
});

test('optional pasal off and extra pasal on', function () {
    $spk = ProjectSpk::factory()->create([
        'server_enabled' => false, 'correspondence_enabled' => false, 'maintenance_months' => 0, 'reference_document' => '', 'tax_note' => false,
        'installments' => [], 'bank_account_id' => null,
        'extra_pasal' => [['id' => 'x1', 'title' => 'Garansi', 'body' => 'Garansi 3 bulan.']],
        'payment_notes' => 'Biaya transport ditanggung PIHAK PERTAMA.',
    ]);

    $data = SpkDocumentData::build($spk);

    expect(array_column($data->pasal, 'title'))->toBe([
        'ASAS KERJA SAMA', 'POKOK PERJANJIAN', 'JANGKA WAKTU', 'HAK DAN KEWAJIBAN', 'NILAI KONTRAK DAN CARA PEMBAYARAN',
        'KERAHASIAAN', 'PENYELESAIAN PERSELISIHAN', 'GARANSI', 'LAIN-LAIN',
    ])
        ->and(spkPasalText($data->pasal[1]))->not->toContain('Spesifikasi lengkap')
        ->and(spkPasalText($data->pasal[2]))->not->toContain('maintenance')
        ->and(spkPasalText($data->pasal[4]))->not->toContain('Harga belum termasuk Pajak')
        ->and(spkPasalText($data->pasal[4]))->toContain('3. Biaya transport ditanggung PIHAK PERTAMA.')
        ->and(spkPasalText($data->pasal[4]))->not->toContain('Nomor rekening')
        ->and($data->signaturePath)->toBeNull();
});

test('a party without a description gets no recital line', function () {
    $data = SpkDocumentData::build(ProjectSpk::factory()->create(['party1_description' => '']));

    expect(array_map('spkText', $data->recitals))->toHaveCount(2)
        ->and(spkText($data->recitals[0]))->toStartWith('Bahwa PIHAK KEDUA adalah')
        ->and($data->recitals[1]['marker'])->toBe('2.');
});

test('lampiran numbers existing attachments only', function () {
    Storage::disk('local')->put('projects/1/spk/attachments/a.png', sphPng(10, 10));
    $spk = ProjectSpk::factory()->create(['attachments' => [
        ['path' => 'projects/1/spk/attachments/a.png', 'title' => 'Struktur Organisasi'],
        ['path' => 'projects/1/spk/attachments/missing.png', 'title' => 'Gone'],
    ]]);

    $data = SpkDocumentData::build($spk);

    expect($data->attachments)->toHaveCount(1)
        ->and($data->attachments[0]['lampiran'])->toBe(1)
        ->and($data->attachments[0]['title'])->toBe('Struktur Organisasi');
});
