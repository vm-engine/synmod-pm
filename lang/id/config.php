<?php

declare(strict_types=1);

return [
    'tabs' => [
        'doc_no_format' => 'Format Nomor Dokumen',
        'general' => 'Umum',
    ],
    'doc_no_format' => [
        'title' => 'Konfigurasi Format Nomor Dokumen',
        'description' => 'Atur format dan penomoran dokumen terkait proyek.',
        'fields' => [
            'label' => 'Jenis Dokumen',
            'number_format' => 'Format Nomor',
            'next_c_index' => 'Indeks Klien Berikutnya',
            'next_nc_index' => 'Indeks Non Klien Berikutnya',
        ],
        'placeholders' => [
            'number_format' => 'Mis. {DOC_CODE}/{CLIENT_CODE}/{YEAR}/{INDEX}',
        ],
        'help' => [
            'title' => 'Placeholder yang Tersedia',
            'number_format' => 'Atur format nomor dokumen menggunakan placeholder di bawah.',
            'index' => 'Nomor urut otomatis',
            'project_code' => 'Kode proyek',
            'division_code' => 'Kode divisi',
            'doc_code' => 'Kode jenis dokumen',
            'cnc' => 'Penanda klien/non-klien',
            'client_id' => 'ID klien',
            'mr' => 'Bulan dalam angka Romawi',
            'yyyy' => 'Tahun (4 digit)',
            'yy' => 'Tahun (2 digit)',
        ],
        'buttons' => [
            'save' => 'Simpan Konfigurasi',
        ],
        'messages' => [
            'save_success_title' => 'Konfigurasi Disimpan',
            'save_success_message' => 'Konfigurasi format nomor dokumen berhasil disimpan.',
        ],
    ],
    'general' => [
        'title' => 'Konfigurasi Umum Faktur',
        'description' => 'Nilai bawaan yang digunakan saat membuat faktur baru.',
        'sections' => [
            'tax' => [
                'title' => 'Pajak',
                'description' => 'Nilai bawaan perhitungan PPN dan DPP.',
            ],
            'defaults' => [
                'title' => 'Bawaan Faktur',
                'description' => 'Nilai yang dipilih otomatis pada formulir faktur baru.',
            ],
        ],
        'fields' => [
            'vat_percentage' => 'Persentase PPN',
            'include_vat_default' => 'Sertakan PPN secara bawaan',
            'dpp_numerator' => 'Pembilang DPP',
            'dpp_denominator' => 'Penyebut DPP',
            'payment_due_days' => 'Jatuh Tempo Pembayaran (hari)',
            'default_issuer_id' => 'Penerbit Bawaan',
            'default_bank_account_id' => 'Rekening Bank Bawaan',
            'default_signature_id' => 'Tanda Tangan Bawaan',
        ],
        'help' => [
            'dpp_ratio' => 'Digunakan untuk perhitungan DPP Nilai Lain. Bawaan 11/12 sesuai peraturan PPN Indonesia yang berlaku.',
            'payment_due_days' => 'Jumlah hari yang ditambahkan ke tanggal terbit untuk menghitung tanggal jatuh tempo.',
            'default_none' => 'Tanpa bawaan',
        ],
        'buttons' => [
            'save' => 'Simpan Konfigurasi',
        ],
        'messages' => [
            'save_success_title' => 'Konfigurasi Disimpan',
            'save_success_message' => 'Konfigurasi faktur berhasil disimpan.',
        ],
        'validation' => [
            'vat_percentage_range' => 'Persentase PPN harus antara 0 dan 100.',
            'dpp_numerator_min' => 'Pembilang DPP minimal 1.',
            'dpp_denominator_min' => 'Penyebut DPP minimal 1.',
            'payment_due_days_min' => 'Jumlah hari jatuh tempo pembayaran tidak boleh negatif.',
            'issuer_exists' => 'Penerbit yang dipilih tidak ada.',
            'bank_account_exists' => 'Rekening bank yang dipilih tidak ada.',
            'signature_exists' => 'Tanda tangan yang dipilih tidak ada.',
        ],
    ],
];
