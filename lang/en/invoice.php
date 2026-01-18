<?php

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
    'receipts' => 'Receipts',

    // Table columns
    'col_no' => 'No',
    'col_invoice_code' => 'Invoice Code',
    'col_total' => 'Total Invoice',
    'col_issue_date' => 'Issue Date',
    'col_due_date' => 'Due Date',
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
    'not_found' => 'Invoice not found',

    // Delete confirmation
    'delete_title' => 'Delete Invoice',
    'delete_message' => 'Are you sure you want to delete invoice :code? This action cannot be undone.',

    // Form fields
    'spk_number' => 'SPK Number',
    'spk_number_hint' => 'Auto-filled from SPK document',
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

    // Validation messages
    'validation' => [
        'invoice_number_required' => 'Invoice number is required',
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
