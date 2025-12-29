<?php

return [
    // Page titles
    'list_title' => 'Project Documents',
    'create_title' => 'Upload Document',
    'edit_title' => 'Edit Document',
    'form_title' => 'Document Form',
    'document_information' => 'Document Information',

    // Field labels
    'project' => 'Project',
    'client' => 'Client',
    'user' => 'User',
    'type' => 'Document Type',
    'number' => 'Document Number',
    'notes' => 'Notes',
    'file' => 'File',
    'file_path' => 'File Path',
    'file_info' => 'File Information',
    'version' => 'Version',
    'created_by' => 'Created By',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',

    // Table columns
    'col_no' => 'No.',
    'col_type' => 'Type',
    'col_number' => 'Number',
    'col_version' => 'Version',
    'col_file' => 'File',
    'col_notes' => 'Notes',
    'col_created_by' => 'Created By',
    'col_created_at' => 'Created',
    'col_actions' => 'Actions',

    // Placeholders
    'search_placeholder' => 'Search documents...',
    'select_type' => 'Select document type',
    'notes_placeholder' => 'Enter notes about this document',

    // Buttons
    'upload_document' => 'Upload Document',
    'download' => 'Download',
    'view' => 'View',
    'save' => 'Save',
    'saving' => 'Saving...',
    'uploading' => 'Uploading...',
    'cancel' => 'Cancel',
    'upload_new_version' => 'Upload New Version',

    // Filter labels
    'filter_type' => 'Document Type',
    'filter_project' => 'Project',
    'filter_client' => 'Client',
    'all_types' => '-- All Types --',

    // Messages
    'upload_success' => 'Document uploaded successfully.',
    'update_success' => 'Document updated successfully.',
    'delete_success' => 'Document deleted successfully.',
    'delete_confirm' => 'Are you sure you want to delete this document?',
    'no_documents' => 'No documents found.',
    'version_info' => 'Version :version',

    // Help text
    'file_help' => 'Supported formats: PDF, DOC, DOCX (Max size: 10MB)',
    'number_help' => 'Document number will be auto-generated if left blank',
    'version_help' => 'Version will be automatically incremented',

    // Tooltips
    'tooltip_download' => 'Download',
    'tooltip_view' => 'View',
    'tooltip_edit' => 'Edit',
    'tooltip_delete' => 'Delete',
    'tooltip_upload_version' => 'Upload new version',

    // Document types (from Documents enum)
    'types' => [
        'proposal' => 'Proposal',
        'sph' => 'Surat Penawaran Harga (SPH)',
        'spk' => 'Surat Perjanjian Kerjasama',
        'sow' => 'Scope of Work (SOW)/Kerangka Acuan Kerja (KAK)',
        'tor' => 'Term of Requirement (TOR)',
        'use_case' => 'Use Case Document',
        'blueprint' => 'Blueprint',
        'invoice' => 'Invoice',
        'receipt' => 'Kwitansi',
        'bast' => 'Berita Acara Serah Terima (BAST)',
        'panduan' => 'Panduan Penggunaan',
        'change_request' => 'Change Request Document',
        'backup' => 'Backup Source Code',
        'uat' => 'User Acceptance Test (UAT)',
        'ba' => 'Berita Acara (BA)',
    ],
];
