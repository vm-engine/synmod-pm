<?php

declare(strict_types=1);

return [
    'title' => 'Manajemen Penerbit Dokumen',
    'list_title' => 'Penerbit Dokumen',
    'create_title' => 'Tambah Penerbit Dokumen',
    'edit_title' => 'Ubah Penerbit Dokumen',
    'header_image' => 'Gambar Kop',
    'current_image' => 'Gambar Kop Saat Ini',
    'footer_image' => 'Gambar Footer',
    'current_footer_image' => 'Gambar Footer Saat Ini',
    'letterhead_hint' => 'Dokumen SPH menempatkan kop dan footer selebar penuh A4: gunakan desain kop surat selebar halaman.',
    'drop_image_here' => 'Letakkan gambar kop di sini',
    'image_description' => 'Seret dan letakkan gambar PNG, JPG, WebP Anda di sini atau telusuri (maks 5MB, disarankan format lanskap)',
    'tax_id_name' => 'Nama NPWP',
    'tax_id_address' => 'Alamat NPWP',
    'tax_id_number' => 'Nomor NPWP',
    'tax_id_format' => 'Angka 16 digit',
    'search_placeholder' => 'Cari berdasarkan nama atau NPWP...',
    'delete_title' => 'Hapus Penerbit Dokumen',
    'delete_message' => 'Apakah Anda yakin ingin menghapus :name? Tindakan ini tidak dapat dibatalkan.',
    'no_data' => 'Penerbit dokumen tidak ditemukan',
    'created_successfully' => 'Penerbit dokumen berhasil dibuat',
    'updated_successfully' => 'Penerbit dokumen berhasil diperbarui',
    'deleted_successfully' => 'Penerbit dokumen berhasil dihapus',
    'not_found' => 'Penerbit dokumen tidak ditemukan',
    'name_placeholder' => 'Masukkan nama penerbit',
    'tax_id_name_placeholder' => 'Masukkan nama resmi sesuai NPWP',
    'tax_id_address_placeholder' => 'Masukkan alamat NPWP',

    // Validation messages
    'validation' => [
        'name_required' => 'Nama penerbit wajib diisi',
        'header_image_required' => 'Gambar kop wajib diisi',
        'header_image_file' => 'Gambar kop harus berupa berkas yang valid',
        'header_image_mimes' => 'Gambar kop harus berupa berkas PNG, JPG, atau WebP',
        'header_image_max' => 'Gambar kop tidak boleh melebihi 5MB',
        'footer_image_file' => 'Gambar footer harus berupa berkas yang valid',
        'footer_image_mimes' => 'Gambar footer harus berupa berkas PNG, JPG, atau WebP',
        'footer_image_max' => 'Gambar footer tidak boleh melebihi 5MB',
        'tax_id_name_required' => 'Nama NPWP wajib diisi',
        'tax_id_address_required' => 'Alamat NPWP wajib diisi',
        'tax_id_number_required' => 'Nomor NPWP wajib diisi',
        'tax_id_number_numeric' => 'Nomor NPWP hanya boleh berisi angka',
        'tax_id_number_digits' => 'Nomor NPWP harus tepat 16 digit',
    ],
];
