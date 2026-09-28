<?php

declare(strict_types=1);

return [
    // Page titles
    'list_title' => 'Invoices',
    'create_title' => 'Create Invoice',
    'edit_title' => 'Edit Invoice',
    'detail_title' => 'Invoice Detail',

    // Buttons
    'add_invoice' => 'Add Invoice',
    'download' => 'Download',
    'print' => 'Print',
    'lang_indonesian' => 'Bahasa Indonesia',
    'lang_english' => 'English',
    'receipts' => 'Receipts',
    'archive' => 'Archive',
    'unarchive' => 'Unarchive',
    'delete' => 'Delete',
    'save_and_generate_pdf' => 'Save & Generate PDF',
    'add_item' => 'Add Item',

    // Table columns
    'col_no' => 'No',
    'col_invoice_code' => 'Invoice Code',
    'col_total' => 'Total Invoice',
    'col_vat' => 'VAT',
    'col_dates' => 'Issue / Due',
    'col_issue_date' => 'Issue Date',
    'col_due_date' => 'Due Date',
    'paid_on' => 'Paid On',
    'sub_total' => 'Sub Total',
    'vat' => 'VAT',
    'dpp' => 'DPP',
    'discount' => 'Discount',
    'col_paid_amount' => 'Paid Amount',
    'col_status' => 'Status',
    'col_issuer' => 'Issuer',
    'col_creator' => 'Creator',
    'col_actions' => 'Actions',

    // Status labels
    'status_paid' => 'Paid',
    'status_unpaid' => 'Unpaid',
    'status_partial' => 'Partially Paid',

    // Filters
    'advanced_filter' => 'Advanced Filter',
    'filter_status' => 'Payment Status',
    'filter_year' => 'Year',
    'filter_issuer' => 'Issuer',
    'filter_creator' => 'Creator',
    'select_issuer' => 'Select Issuer',
    'select_creator' => 'Select Creator',
    'all' => 'All',

    // Search
    'search_placeholder' => 'Search by invoice code...',

    // Tooltips
    'tooltip_detail' => 'View Detail',
    'tooltip_edit' => 'Edit Invoice',
    'tooltip_delete' => 'Delete Invoice',
    'tooltip_more' => 'More Actions',

    // Messages
    'no_data' => 'No invoices found',
    'created_successfully' => 'Invoice created successfully',
    'updated_successfully' => 'Invoice updated successfully',
    'deleted_successfully' => 'Invoice deleted successfully',
    'archived_successfully' => 'Invoice archived successfully',
    'unarchived_successfully' => 'Invoice unarchived successfully',
    'not_found' => 'Invoice not found',
    'pdf_generated' => 'PDF generated successfully',
    'pdf_not_found' => 'Invoice PDF not found',
    'item_added' => 'Item added successfully',
    'item_updated' => 'Item updated successfully',
    'item_deleted' => 'Item deleted successfully',

    // Delete confirmation
    'delete_title' => 'Delete Invoice',
    'delete_message' => 'Are you sure you want to delete invoice :code? This action cannot be undone.',
    'delete_item_title' => 'Delete Item',
    'delete_item_message' => 'Are you sure you want to delete this invoice item?',

    // Archive confirmation
    'archive_title' => 'Archive Invoice',
    'archive_message' => 'Are you sure you want to archive invoice :code? Archived invoices will not be counted on reports',
    'unarchive_title' => 'Unarchive Invoice',
    'unarchive_message' => 'Are you sure you want to unarchive invoice :code?',
    'archived_detail' => 'This invoice is archived. To make changes, please unarchive it first.',

    // Form fields
    'spk_number' => 'SPK Number',
    'spk_number_hint' => 'Auto-filled from SPK document',
    'spk_number_manual_hint' => 'Enter the external SPK number.',
    'spk_number_placeholder' => 'Enter SPK number',
    'external_spk' => 'From External SPK',
    'currency' => 'Currency',
    'select_currency' => 'Select currency',
    'exchange_rate' => 'Exchange Rate (IDR)',
    'grand_total_idr' => 'Grand Total (IDR)',
    'invoice_number' => 'Invoice Number',
    'invoice_number_placeholder' => 'Enter invoice number',
    'recipient' => 'Recipient',
    'recipient_placeholder' => 'Enter recipient name',
    'address' => 'Address',
    'address_placeholder' => 'Enter recipient address',
    'issue_date' => 'Issue Date',
    'due_date' => 'Due Date',
    'attention' => 'Attention',
    'attention_placeholder' => 'Enter attention name',
    'notes' => 'Notes',
    'notes_placeholder' => 'Enter additional notes (optional)',
    'issuer' => 'Issuer',
    'select_issuer' => 'Select Issuer',
    'bank_account' => 'Bank Account',
    'select_bank_account' => 'Select Bank Account',
    'has_signature' => 'Include Signature',
    'signature' => 'Signature',
    'select_signature' => 'Select Signature',

    // Invoice detail
    'items_title' => 'Invoice Items',
    'item_name' => 'Description',
    'item_name_placeholder' => 'Enter item description',
    'item_amount' => 'Amount',
    'item_discount' => 'Discount',
    'item_total' => 'Total',

    // Totals section
    'totals_title' => 'Invoice Totals',
    'sub_total_items' => 'Sub Total (Items)',
    'include_dpp' => 'DPP',
    'include_discount' => 'Invoice Discount',
    'after_discount' => 'After Discount',
    'include_vat' => 'VAT',
    'grand_total' => 'Grand Total',

    // PDF translations
    'pdf' => [
        'to' => 'To',
        'address' => 'Address',
        'attention' => 'Attention',
        'spk_no' => 'SPK No',
        'date' => 'Date',
        'due_date' => 'Due Date',
        'description' => 'Description',
        'amount' => 'Amount',
        'discount' => 'Discount',
        'total' => 'Total',
        'sub_total' => 'Sub Total',
        'dpp' => 'DPP',
        'vat' => 'VAT',
        'grand_total' => 'Grand Total',
        'notes' => 'Notes',
        'payment_method' => 'Payment Method',
        'bank_transfer' => 'Bank Transfer',
        'bank_name' => 'Bank Name',
        'account_name' => 'Account Name',
        'account_number' => 'Account Number',
    ],

    // Validation messages
    'validation' => [
        'invoice_number_required' => 'Invoice number is required',
        'currency_required' => 'Currency is required.',
        'currency_locked' => 'Currency cannot be changed after items have been added.',
        'spk_document_required' => 'SPK document is required.',
        'spk_number_required' => 'SPK number is required.',
        'recipient_required' => 'Recipient is required',
        'address_required' => 'Address is required',
        'issue_date_required' => 'Issue date is required',
        'issue_date_date' => 'Issue date must be a valid date',
        'due_date_required' => 'Due date is required',
        'due_date_date' => 'Due date must be a valid date',
        'due_date_after' => 'Due date must be on or after the issue date',
        'issuer_required' => 'Please select an issuer',
        'issuer_exists' => 'Selected issuer is invalid',
        'bank_account_required' => 'Please select a bank account',
        'bank_account_exists' => 'Selected bank account is invalid',
        'signature_required' => 'Please select a signature when signature is enabled',
        'signature_exists' => 'Selected signature is invalid',
    ],
];
