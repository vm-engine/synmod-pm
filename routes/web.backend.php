<?php

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Livewire\ClientForm;
use VmEngine\Pm\Livewire\ClientList;
use VmEngine\Pm\Livewire\Config;
use VmEngine\Pm\Livewire\Master\ClientCategory;
use VmEngine\Pm\Livewire\Master\Division;
use VmEngine\Pm\Livewire\Master\ProjectCategory;
use VmEngine\Pm\Livewire\Project\ProjectDetail;
use VmEngine\Pm\Livewire\Project\ProjectForm;
use VmEngine\Pm\Livewire\Project\ProjectList;

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

    Route::get('/config', Config::class)
        ->name('config')->middleware('can-access:pm.config.read');
});
