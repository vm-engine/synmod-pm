<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', function () {
        return view('pm::livewire.index');
    })->name('index')->middleware('can-access:pm.manage');
});
