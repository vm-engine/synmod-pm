<?php

declare(strict_types=1);

return [
    'title' => 'Document Issuer Management',
    'list_title' => 'Document Issuers',
    'create_title' => 'Add Document Issuer',
    'edit_title' => 'Edit Document Issuer',
    'header_image' => 'Header Image',
    'current_image' => 'Current Header Image',
    'drop_image_here' => 'Drop header image here',
    'image_description' => 'Drag and drop your PNG, JPG, WebP image here or browse (max 5MB, landscape format recommended)',
    'tax_id_name' => 'Tax ID Name',
    'tax_id_address' => 'Tax ID Address',
    'tax_id_number' => 'Tax ID Number (NPWP)',
    'tax_id_format' => '16 digits number',
    'search_placeholder' => 'Search by name or tax ID...',
    'delete_title' => 'Delete Document Issuer',
    'delete_message' => 'Are you sure you want to delete :name? This action cannot be undone.',
    'no_data' => 'No document issuers found',
    'created_successfully' => 'Document issuer created successfully',
    'updated_successfully' => 'Document issuer updated successfully',
    'deleted_successfully' => 'Document issuer deleted successfully',
    'not_found' => 'Document issuer not found',
    'name_placeholder' => 'Enter issuer name',
    'tax_id_name_placeholder' => 'Enter legal name for tax ID',
    'tax_id_address_placeholder' => 'Enter tax ID address',

    // Validation messages
    'validation' => [
        'name_required' => 'Issuer name is required',
        'header_image_required' => 'Header image is required',
        'header_image_file' => 'Header image must be a valid file',
        'header_image_mimes' => 'Header image must be a PNG, JPG, or WebP file',
        'header_image_max' => 'Header image must not exceed 5MB',
        'tax_id_name_required' => 'Tax ID name is required',
        'tax_id_address_required' => 'Tax ID address is required',
        'tax_id_number_required' => 'Tax ID number is required',
        'tax_id_number_numeric' => 'Tax ID number must contain only numbers',
        'tax_id_number_digits' => 'Tax ID number must be exactly 16 digits',
    ],
];
