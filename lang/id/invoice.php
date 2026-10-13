<?php

declare(strict_types=1);

return [
    // Page titles
    'list_title' => 'Faktur',
    'create_title' => 'Buat Faktur',
    'edit_title' => 'Ubah Faktur',
    'detail_title' => 'Detail Faktur',

    // Buttons
    'add_invoice' => 'Tambah Faktur',
    'download' => 'Unduh',
    'print' => 'Cetak',
    'lang_indonesian' => 'Bahasa Indonesia',
    'lang_english' => 'Bahasa Inggris',
    'receipts' => 'Kuitansi',
    'archive' => 'Arsipkan',
    'unarchive' => 'Batalkan Arsip',
    'delete' => 'Hapus',
    'save_and_generate_pdf' => 'Simpan & Buat PDF',
    'add_item' => 'Tambah Item',

    // Table columns
    'col_no' => 'No',
    'col_invoice_code' => 'Kode Faktur',
    'col_total' => 'Total Faktur',
    'col_vat' => 'PPN',
    'col_dates' => 'Terbit / Jatuh Tempo',
    'col_issue_date' => 'Tanggal Terbit',
    'col_due_date' => 'Jatuh Tempo',
    'paid_on' => 'Dibayar Pada',
    'sub_total' => 'Sub Total',
    'vat' => 'PPN',
    'dpp' => 'DPP',
    'discount' => 'Diskon',
    'col_paid_amount' => 'Jumlah Dibayar',
    'col_status' => 'Status',
    'col_issuer' => 'Penerbit',
    'col_creator' => 'Pembuat',
    'col_actions' => 'Aksi',

    // Status labels
    'status_paid' => 'Lunas',
    'status_unpaid' => 'Belum Dibayar',
    'status_partial' => 'Dibayar Sebagian',

    // Filters
    'advanced_filter' => 'Filter Lanjutan',
    'filter_status' => 'Status Pembayaran',
    'filter_year' => 'Tahun',
    'filter_issuer' => 'Penerbit',
    'filter_creator' => 'Pembuat',
    'select_creator' => 'Pilih Pembuat',
    'all' => 'Semua',

    // Search
    'search_placeholder' => 'Cari berdasarkan kode faktur...',

    // Tooltips
    'tooltip_detail' => 'Lihat Detail',
    'tooltip_edit' => 'Ubah Faktur',
    'tooltip_delete' => 'Hapus Faktur',
    'tooltip_more' => 'Aksi Lainnya',

    // Messages
    'no_data' => 'Faktur tidak ditemukan',
    'created_successfully' => 'Faktur berhasil dibuat',
    'updated_successfully' => 'Faktur berhasil diperbarui',
    'deleted_successfully' => 'Faktur berhasil dihapus',
    'archived_successfully' => 'Faktur berhasil diarsipkan',
    'unarchived_successfully' => 'Arsip faktur berhasil dibatalkan',
    'not_found' => 'Faktur tidak ditemukan',
    'pdf_generated' => 'PDF berhasil dibuat',
    'pdf_not_found' => 'PDF faktur tidak ditemukan',
    'item_added' => 'Item berhasil ditambahkan',
    'item_updated' => 'Item berhasil diperbarui',
    'item_deleted' => 'Item berhasil dihapus',

    // Delete confirmation
    'delete_title' => 'Hapus Faktur',
    'delete_message' => 'Apakah Anda yakin ingin menghapus faktur :code? Tindakan ini tidak dapat dibatalkan.',
    'delete_item_title' => 'Hapus Item',
    'delete_item_message' => 'Apakah Anda yakin ingin menghapus item faktur ini?',

    // Archive confirmation
    'archive_title' => 'Arsipkan Faktur',
    'archive_message' => 'Apakah Anda yakin ingin mengarsipkan faktur :code? Faktur yang diarsipkan tidak akan dihitung dalam laporan',
    'unarchive_title' => 'Batalkan Arsip Faktur',
    'unarchive_message' => 'Apakah Anda yakin ingin membatalkan arsip faktur :code?',
    'archived_detail' => 'Faktur ini diarsipkan. Untuk melakukan perubahan, batalkan arsipnya terlebih dahulu.',

    // Form fields
    'spk_number' => 'Nomor SPK',
    'spk_number_hint' => 'Terisi otomatis dari dokumen SPK',
    'spk_number_manual_hint' => 'Masukkan nomor SPK eksternal.',
    'spk_number_placeholder' => 'Masukkan nomor SPK',
    'external_spk' => 'Dari SPK Eksternal',
    'currency' => 'Mata Uang',
    'select_currency' => 'Pilih mata uang',
    'exchange_rate' => 'Kurs (IDR)',
    'grand_total_idr' => 'Total Keseluruhan (IDR)',
    'invoice_number' => 'Nomor Faktur',
    'invoice_number_placeholder' => 'Masukkan nomor faktur',
    'recipient' => 'Penerima',
    'recipient_placeholder' => 'Masukkan nama penerima',
    'address' => 'Alamat',
    'address_placeholder' => 'Masukkan alamat penerima',
    'issue_date' => 'Tanggal Terbit',
    'due_date' => 'Jatuh Tempo',
    'attention' => 'Perhatian',
    'attention_placeholder' => 'Masukkan nama yang dituju',
    'notes' => 'Catatan',
    'notes_placeholder' => 'Masukkan catatan tambahan (opsional)',
    'issuer' => 'Penerbit',
    'select_issuer' => 'Pilih Penerbit',
    'bank_account' => 'Rekening Bank',
    'select_bank_account' => 'Pilih Rekening Bank',
    'has_signature' => 'Sertakan Tanda Tangan',
    'signature' => 'Tanda Tangan',
    'select_signature' => 'Pilih Tanda Tangan',

    // Invoice detail
    'items_title' => 'Item Faktur',
    'item_name' => 'Deskripsi',
    'item_name_placeholder' => 'Masukkan deskripsi item',
    'item_amount' => 'Jumlah',
    'item_discount' => 'Diskon',
    'item_total' => 'Total',

    // Totals section
    'totals_title' => 'Total Faktur',
    'sub_total_items' => 'Sub Total (Item)',
    'include_dpp' => 'DPP',
    'include_discount' => 'Diskon Faktur',
    'after_discount' => 'Setelah Diskon',
    'include_vat' => 'PPN',
    'grand_total' => 'Total Keseluruhan',

    // PDF translations
    'pdf' => [
        'to' => 'Kepada',
        'address' => 'Alamat',
        'attention' => 'Perhatian',
        'spk_no' => 'No SPK',
        'date' => 'Tanggal',
        'due_date' => 'Jatuh Tempo',
        'description' => 'Deskripsi',
        'amount' => 'Jumlah',
        'discount' => 'Diskon',
        'total' => 'Total',
        'sub_total' => 'Sub Total',
        'dpp' => 'DPP',
        'vat' => 'PPN',
        'grand_total' => 'Total Keseluruhan',
        'notes' => 'Catatan',
        'payment_method' => 'Metode Pembayaran',
        'bank_transfer' => 'Transfer Bank',
        'bank_name' => 'Nama Bank',
        'account_name' => 'Nama Rekening',
        'account_number' => 'Nomor Rekening',
    ],

    // Validation messages
    'validation' => [
        'invoice_number_required' => 'Nomor faktur wajib diisi',
        'currency_required' => 'Mata uang wajib diisi.',
        'currency_locked' => 'Mata uang tidak dapat diubah setelah item ditambahkan.',
        'spk_document_required' => 'Dokumen SPK wajib diisi.',
        'spk_number_required' => 'Nomor SPK wajib diisi.',
        'recipient_required' => 'Penerima wajib diisi',
        'address_required' => 'Alamat wajib diisi',
        'issue_date_required' => 'Tanggal terbit wajib diisi',
        'issue_date_date' => 'Tanggal terbit harus berupa tanggal yang valid',
        'due_date_required' => 'Jatuh tempo wajib diisi',
        'due_date_date' => 'Jatuh tempo harus berupa tanggal yang valid',
        'due_date_after' => 'Jatuh tempo harus sama dengan atau setelah tanggal terbit',
        'issuer_required' => 'Silakan pilih penerbit',
        'issuer_exists' => 'Penerbit yang dipilih tidak valid',
        'bank_account_required' => 'Silakan pilih rekening bank',
        'bank_account_exists' => 'Rekening bank yang dipilih tidak valid',
        'signature_required' => 'Silakan pilih tanda tangan jika tanda tangan diaktifkan',
        'signature_exists' => 'Tanda tangan yang dipilih tidak valid',
    ],
];
