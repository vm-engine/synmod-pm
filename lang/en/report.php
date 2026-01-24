<?php

return [
    'documents' => [
        'title' => 'Project Documents',
        'filters' => [
            'label' => 'Filters',
            'year' => 'Year',
            'type' => 'Document Type',
            'project_category' => 'Project Category',
            'reset' => 'Reset',
            'results' => 'Showing :count result(s)',
        ],
        'table' => [
            'document' => 'Document',
            'client' => 'Client',
            'project' => 'Project',
            'category' => 'Category',
            'status' => 'Status',
            'uncategorized' => 'Uncategorized',
            'no_data' => 'No Data',
        ],
        'status' => [
            'deal' => 'Deal',
            'pending' => 'Pending',
        ],
    ],
    'invoice' => [
        'title' => 'Invoice Report :year',
        'statistics' => 'Statistics',
        'statistics_description' => 'Overview of invoice statistics for the selected year',
        'total' => 'Total',
        'project_categories' => 'Project Categories',
        'invoice_amount' => 'Invoice Amount',
        'invoices' => 'invoices',
        'no_data' => 'No data available for the selected year',
        'stats' => [
            'total_invoices' => 'Total Invoices',
            'total_unpaid' => 'Total Unpaid Amount',
            'total_paid' => 'Total Paid Amount',
            'total_receipts' => 'Total Receipts Issued',
            'vat_total' => 'Total VAT Collected',
            'total_amount' => 'Total Amount Invoiced',
        ],
        'by_creator' => 'Invoices by Creator',
        'by_project' => 'Invoices by Project',
        'filters' => [
            'label' => 'Filters',
            'year' => 'Year',
        ],
    ],
];
