<?php

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Livewire\ClientForm;
use VmEngine\Pm\Livewire\ClientList;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;

Route::group([], function () {
    Route::get('/', function () {
        return view('pm::livewire.index');
    })->name('index')->middleware('can-access:pm.manage');

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
});
