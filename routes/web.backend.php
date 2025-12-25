<?php

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;

Route::group([], function () {
    Route::get('/', function () {
        return view('pm::livewire.index');
    })->name('index')->middleware('can-access:pm.manage');
    Route::get('/master/division', Division::class)
        ->name('master.division')->middleware('can-access:pm.master.division');
    Route::get('/master/client-category', ClientCategory::class)
        ->name('master.client_category')->middleware('can-access:pm.master.client_category');
});
