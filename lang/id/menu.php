<?php

declare(strict_types=1);

return [
    'be' => [
        'parent' => 'Manajemen Proyek',
        'index' => 'Daftar Pm',
        'project' => [
            'index' => 'Proyek',
            'create' => 'Buat Proyek',
            'edit' => 'Ubah Proyek',
            'detail' => 'Detail Proyek',
        ],
        'client' => [
            'index' => 'Klien',
            'create' => 'Buat Klien',
            'edit' => 'Ubah Klien',
        ],
        'master' => [
            'parent' => 'Data Master',
            'division' => 'Divisi',
            'client_category' => 'Kategori Klien',
            'project_category' => 'Kategori Proyek',
            'issuer' => 'Penerbit Dokumen',
            'bank_account' => 'Rekening Bank',
            'signature' => 'Tanda Tangan',
            'holiday' => 'Hari Libur',
            'proposal_feature' => 'Fitur Proposal',
            'proposal_default' => 'Bawaan Proposal',
        ],
        'config' => [
            'index' => 'Konfigurasi',
        ],
        'report' => [
            'index' => 'Laporan',
            'project_document' => 'Dokumen Proyek',
            'invoice' => 'Laporan Faktur',
        ],

    ],
];
