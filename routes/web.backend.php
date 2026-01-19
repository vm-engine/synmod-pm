<?php

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Http\Controllers\DocumentController;
use VmEngine\Pm\Http\Controllers\InvoiceController;
use VmEngine\Pm\Http\Controllers\ReceiptController;
use VmEngine\Pm\Livewire\ClientForm;
use VmEngine\Pm\Livewire\ClientList;
use VmEngine\Pm\Livewire\Config;
use VmEngine\Pm\Livewire\Master\BankAccount;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;
use VmEngine\Pm\Livewire\Master\Issuer;
use VmEngine\Pm\Livewire\Master\ProjectCategory;
use VmEngine\Pm\Livewire\Master\Signature;
use VmEngine\Pm\Livewire\Project\ProjectDetail;
use VmEngine\Pm\Livewire\Project\ProjectForm;
use VmEngine\Pm\Livewire\Project\ProjectList;
use VmEngine\Pm\Livewire\Report\ProjectDocument;

Route::group([], function () {
    Route::get('/', function () {
        return view('pm::livewire.index');
    })->name('index')->middleware('can-access:pm.manage');

    // Project routes
    Route::get('/project', ProjectList::class)
        ->name('project.index')->middleware('can-access:pm.project.read');
    Route::get('/project/create', ProjectForm::class)
        ->name('project.create')->middleware('can-access:pm.project.create');
    Route::get('/project/{id}/edit', ProjectForm::class)
        ->name('project.edit')->middleware('can-access:pm.project.update');
    Route::get('/project/{id}', ProjectDetail::class)
        ->name('project.detail')->middleware('can-access:pm.project.read');

    // Document Download routes
    Route::get('/document/{id}/download', [DocumentController::class, 'download'])
        ->name('document.download')->middleware('can-access:pm.document.read');
    Route::get('/document/{id}/print', [DocumentController::class, 'print'])
        ->name('document.print')->middleware('can-access:pm.document.read');

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
    Route::get('/client', ClientList::class)
        ->name('client.index')->middleware('can-access:pm.client.read');
    Route::get('/client/create', ClientForm::class)
        ->name('client.create')->middleware('can-access:pm.client.create');
    Route::get('/client/{id}/edit', ClientForm::class)
        ->name('client.edit')->middleware('can-access:pm.client.update');

    // Master data routes
    Route::get('/master/division', Division::class)
        ->name('master.division')->middleware('can-access:pm.master.division');
    Route::get('/master/client-category', ClientCategory::class)
        ->name('master.client_category')->middleware('can-access:pm.master.client_category');
    Route::get('/master/project-category', ProjectCategory::class)
        ->name('master.project_category')->middleware('can-access:pm.master.project_category');
    Route::get('/master/issuer', Issuer::class)
        ->name('master.issuer')->middleware('can-access:pm.master.issuer');
    Route::get('/master/bank-account', BankAccount::class)
        ->name('master.bank_account')->middleware('can-access:pm.master.bank_account');
    Route::get('/master/signature', Signature::class)
        ->name('master.signature')->middleware('can-access:pm.master.signature');

    Route::get('/report/project-document', ProjectDocument::class)
        ->name('report.project_document')->middleware('can-access:pm.report.project_document');

    Route::get('/config', Config::class)
        ->name('config')->middleware('can-access:pm.config.read');
});
