<?php

declare(strict_types=1);

return [
    // Page titles
    'list_title' => 'Dokumen Proyek',
    'create_title' => 'Unggah Dokumen',
    'edit_title' => 'Ubah Dokumen',
    'form_title' => 'Formulir Dokumen',
    'document_information' => 'Informasi Dokumen',

    // Field labels
    'project' => 'Proyek',
    'client' => 'Klien',
    'user' => 'Pengguna',
    'type' => 'Jenis Dokumen',
    'number' => 'Nomor Dokumen',
    'notes' => 'Catatan',
    'file' => 'Berkas',
    'file_path' => 'Path Berkas',
    'file_info' => 'Informasi Berkas',
    'version' => 'Versi',
    'created_by' => 'Dibuat Oleh',
    'created_at' => 'Dibuat Pada',
    'updated_at' => 'Diperbarui Pada',

    // Table columns
    'col_no' => 'No.',
    'col_type' => 'Jenis',
    'col_number' => 'Nomor',
    'col_version' => 'Versi',
    'col_file' => 'Berkas',
    'col_notes' => 'Catatan',
    'col_created_by' => 'Dibuat Oleh',
    'col_created_at' => 'Dibuat',
    'col_actions' => 'Aksi',

    // Placeholders
    'search_placeholder' => 'Cari dokumen...',
    'select_type' => 'Pilih jenis dokumen',
    'notes_placeholder' => 'Masukkan catatan tentang dokumen ini',

    // Buttons
    'upload_document' => 'Unggah Dokumen',
    'download' => 'Unduh',
    'view' => 'Lihat',
    'save' => 'Simpan',
    'saving' => 'Menyimpan...',
    'uploading' => 'Mengunggah...',
    'cancel' => 'Batal',
    'upload_new_version' => 'Unggah Versi Baru',

    // Filter labels
    'filter_type' => 'Jenis Dokumen',
    'filter_project' => 'Proyek',
    'filter_client' => 'Klien',
    'all_types' => '-- Semua Jenis --',

    // Messages
    'upload_success' => 'Dokumen berhasil diunggah.',
    'update_success' => 'Dokumen berhasil diperbarui.',
    'delete_success' => 'Dokumen berhasil dihapus.',
    'delete_confirm' => 'Apakah Anda yakin ingin menghapus dokumen ini?',
    'no_documents' => 'Dokumen tidak ditemukan.',
    'version_info' => 'Versi :version',

    // Help text
    'file_help' => 'Format yang didukung: PDF, DOC, DOCX (ukuran maks: 10MB)',
    'number_help' => 'Nomor dokumen akan dibuat otomatis jika dikosongkan',
    'version_help' => 'Versi akan bertambah secara otomatis',

    // Tooltips
    'tooltip_download' => 'Unduh',
    'tooltip_view' => 'Lihat',
    'tooltip_edit' => 'Ubah',
    'tooltip_delete' => 'Hapus',
    'tooltip_upload_version' => 'Unggah versi baru',

    // Empty states
    'no_files_uploaded' => 'Belum ada berkas yang diunggah',
    'no_documents_uploaded' => 'Belum ada dokumen yang diunggah',
    'no_version_history' => 'Riwayat versi tidak tersedia',

    // Action buttons
    'upload_first_file' => 'Unggah Berkas Pertama',
    'upload_first_document' => 'Unggah Dokumen Pertama',
    'add_new_file' => 'Tambah Berkas Baru',
    'new_version' => 'Versi Baru',
    'hide' => 'Sembunyikan',
    'history' => 'Riwayat',

    // File info
    'untitled' => 'Tanpa Judul',
    'untitled_document' => 'Dokumen Tanpa Judul',
    'default_filename' => 'document.pdf',
    'na' => 'N/A',
    'unknown' => 'Tidak Diketahui',
    'current' => 'Saat Ini',

    // Confirmation dialogs
    'delete_file_title' => 'Hapus Berkas',
    'delete_file_message' => 'Apakah Anda yakin ingin menghapus berkas ini? Tindakan ini tidak dapat dibatalkan.',
    'delete_document_title' => 'Hapus Dokumen',
    'delete_document_message' => 'Apakah Anda yakin ingin menghapus dokumen ini? <b>PERINGATAN!</b> Tindakan ini tidak dapat dibatalkan dan akan menghapus semua dokumen yang terkait dengan dokumen ini.',

    // Section titles
    'revision_history' => 'Riwayat Revisi',
    'download_this_version' => 'Unduh versi ini',

    // Document types (from Documents enum)
    'types' => [
        'proposal' => 'Proposal',
        'sph' => 'Surat Penawaran Harga (SPH)',
        'spk' => 'Surat Perjanjian Kerjasama',
        'sow' => 'Scope of Work (SOW)/Kerangka Acuan Kerja (KAK)',
        'tor' => 'Term of Requirement (TOR)',
        'use_case' => 'Dokumen Use Case',
        'blueprint' => 'Blueprint',
        'invoice' => 'Faktur',
        'receipt' => 'Kuitansi',
        'bast' => 'Berita Acara Serah Terima (BAST)',
        'panduan' => 'Panduan Penggunaan',
        'change_request' => 'Dokumen Change Request',
        'backup' => 'Backup Source Code',
        'uat' => 'User Acceptance Test (UAT)',
        'ba' => 'Berita Acara (BA)',
    ],
];
