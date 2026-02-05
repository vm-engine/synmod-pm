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
];
