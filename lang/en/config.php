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
            'number_format' => 'Available placeholders: {INDEX}, {PROJECT_CODE}, {DIVISION_CODE}, {DOC_CODE}, {CNC}, {CLIENT_ID}, {MR}, {YYYY}, {YY}',
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
