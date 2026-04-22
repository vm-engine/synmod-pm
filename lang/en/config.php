<?php

return [
    'tabs' => [
        'doc_no_format' => 'Document Number Format',
        'general' => 'General',
    ],
    'doc_no_format' => [
        'title' => 'Document Number Format Configuration',
        'description' => 'Configure the format and numbering for project-related documents.',
        'fields' => [
            'label' => 'Document Type',
            'number_format' => 'Number Format',
            'next_c_index' => 'Next Client Index',
            'next_nc_index' => 'Next Non Client Index',
        ],
        'placeholders' => [
            'number_format' => 'E.g., {DOC_CODE}/{CLIENT_CODE}/{YEAR}/{INDEX}',
        ],
        'help' => [
            'title' => 'Available Placeholders',
            'number_format' => 'Configure the document number format using placeholders below.',
            'index' => 'Auto-increment number',
            'project_code' => 'Project code',
            'division_code' => 'Division code',
            'doc_code' => 'Document type code',
            'cnc' => 'Client/Non-client indicator',
            'client_id' => 'Client ID',
            'mr' => 'Month in Roman numerals',
            'yyyy' => 'Year (4 digits)',
            'yy' => 'Year (2 digits)',
        ],
        'buttons' => [
            'save' => 'Save Configuration',
        ],
        'messages' => [
            'save_success_title' => 'Configuration Saved',
            'save_success_message' => 'Document number format configuration has been successfully saved.',
        ],
    ],
    'general' => [
        'title' => 'General Invoice Configuration',
        'description' => 'Default values used when creating new invoices.',
        'sections' => [
            'tax' => [
                'title' => 'Tax',
                'description' => 'PPN and DPP calculation defaults.',
            ],
            'defaults' => [
                'title' => 'Invoice Defaults',
                'description' => 'Pre-selected values on the new-invoice form.',
            ],
        ],
        'fields' => [
            'vat_percentage' => 'PPN Percentage',
            'include_vat_default' => 'Include VAT by default',
            'dpp_numerator' => 'DPP Numerator',
            'dpp_denominator' => 'DPP Denominator',
            'payment_due_days' => 'Payment Due (days)',
            'default_issuer_id' => 'Default Issuer',
            'default_bank_account_id' => 'Default Bank Account',
            'default_signature_id' => 'Default Signature',
        ],
        'help' => [
            'dpp_ratio' => 'Used for DPP Nilai Lain calculation. Default 11/12 per current Indonesian PPN regulation.',
            'payment_due_days' => 'Number of days added to the issue date to compute the due date.',
            'default_none' => 'No default',
        ],
        'buttons' => [
            'save' => 'Save Configuration',
        ],
        'messages' => [
            'save_success_title' => 'Configuration Saved',
            'save_success_message' => 'Invoice configuration has been successfully saved.',
        ],
        'validation' => [
            'vat_percentage_range' => 'PPN percentage must be between 0 and 100.',
            'dpp_numerator_min' => 'DPP numerator must be at least 1.',
            'dpp_denominator_min' => 'DPP denominator must be at least 1.',
            'payment_due_days_min' => 'Payment due days cannot be negative.',
            'issuer_exists' => 'Selected issuer does not exist.',
            'bank_account_exists' => 'Selected bank account does not exist.',
            'signature_exists' => 'Selected signature does not exist.',
        ],
    ],
];
