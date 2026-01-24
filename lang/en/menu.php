<?php

return [
    'be' => [
        'parent' => 'Project Management',
        'index' => 'Pm List',
        'project' => [
            'index' => 'Projects',
            'create' => 'Create Project',
            'edit' => 'Edit Project',
            'detail' => 'Project Detail',
        ],
        'client' => [
            'index' => 'Clients',
            'create' => 'Create Client',
            'edit' => 'Edit Client',
        ],
        'master' => [
            'parent' => 'Master Data',
            'division' => 'Divisions',
            'client_category' => 'Client Categories',
            'project_category' => 'Project Categories',
            'issuer' => 'Document Issuers',
            'bank_account' => 'Bank Accounts',
            'signature' => 'Signatures',
        ],
        'config' => [
            'index' => 'Configuration',
        ],
        'report' => [
            'index' => 'Reports',
            'project_document' => 'Project Documents',
            'invoice' => 'Invoice Report',
        ],

    ],
];
