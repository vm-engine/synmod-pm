<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Http\Controllers\DocumentController;
use VmEngine\Pm\Http\Controllers\InvoiceController;
use VmEngine\Pm\Http\Controllers\ReceiptController;

Route::group([], function () {
    // Project routes
    Route::livewire('/project', 'pm::project.project-list')
        ->name('project.index')->middleware('can-access:pm.project.read');
    Route::livewire('/project/create', 'pm::project.project-form')
        ->name('project.create')->middleware('can-access:pm.project.create');
    Route::livewire('/project/{id}/edit', 'pm::project.project-form')
        ->name('project.edit')->middleware('can-access:pm.project.update');
    Route::livewire('/project/{id}', 'pm::project.project-detail')
        ->name('project.detail')->middleware('can-access:pm.project.read');

    // Document Download routes
    Route::get('/document/{id}/download', [DocumentController::class, 'download'])
        ->name('document.download')->middleware('can-access:pm.project.read');
    Route::get('/document/{id}/print', [DocumentController::class, 'print'])
        ->name('document.print')->middleware('can-access:pm.project.read');

    // Invoice routes
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'download'])
        ->name('invoice.download')->middleware('can-access:pm.invoice.read');
    Route::get('/invoice/{id}/print', [InvoiceController::class, 'print'])
        ->name('invoice.print')->middleware('can-access:pm.invoice.read');

    // Receipt routes
    Route::get('/receipt/{id}/download', [ReceiptController::class, 'download'])
        ->name('receipt.download')->middleware('can-access:pm.invoice.read');
    Route::get('/receipt/{id}/print', [ReceiptController::class, 'print'])
        ->name('receipt.print')->middleware('can-access:pm.invoice.read');
    Route::get('/receipt/{id}/payment-proof', [ReceiptController::class, 'paymentProof'])
        ->name('receipt.payment-proof')->middleware('can-access:pm.invoice.read');

    // Client routes
    Route::livewire('/client', 'pm::client-list')
        ->name('client.index')->middleware('can-access:pm.client.read');
    Route::livewire('/client/create', 'pm::client-form')
        ->name('client.create')->middleware('can-access:pm.client.create');
    Route::livewire('/client/{id}/edit', 'pm::client-form')
        ->name('client.edit')->middleware('can-access:pm.client.update');

    // Master data routes
    Route::livewire('/master/division', 'pm::master.division')
        ->name('master.division')->middleware('can-access:pm.master.division');
    Route::livewire('/master/client-category', 'pm::master.client-category')
        ->name('master.client_category')->middleware('can-access:pm.master.client_category');
    Route::livewire('/master/project-category', 'pm::master.project-category')
        ->name('master.project_category')->middleware('can-access:pm.master.project_category');
    Route::livewire('/master/issuer', 'pm::master.issuer-list')
        ->name('master.issuer')->middleware('can-access:pm.master.issuer');
    Route::livewire('/master/bank-account', 'pm::master.bank-account-list')
        ->name('master.bank_account')->middleware('can-access:pm.master.bank_account');
    Route::livewire('/master/signature', 'pm::master.signature-list')
        ->name('master.signature')->middleware('can-access:pm.master.signature');

    // Report routes
    Route::livewire('/report/project-document', 'pm::report.project-document')
        ->name('report.project_document')->middleware('can-access:pm.report.project_document');
    Route::livewire('/report/invoice', 'pm::report.invoice')
        ->name('report.invoice')->middleware('can-access:pm.report.invoice');

    Route::livewire('/config', 'pm::config-page')
        ->name('config')->middleware('can-access:pm.config.read');
});
