<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VmEngine\Pm\Http\Controllers\Api\ClientApiController;
use VmEngine\Pm\Http\Controllers\Api\ClientCategoryApiController;
use VmEngine\Pm\Http\Controllers\Api\InvoiceApiController;
use VmEngine\Pm\Http\Controllers\Api\ServiceApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by SynapseRouteServiceProvider within a group
| which contains the "api" middleware group and the module's prefix.
|
| Prefix: /api/pm
| Name prefix: api.pm.
|
*/

Route::middleware([
    'auth:sanctum',
    'api.auth',
    'api.token.expiry',
    'api.permission:pm.report.invoice.read',
    'throttle:api',
    'pm.api.logger',
])
    ->prefix('invoice')
    ->group(function () {
        Route::get('/statistics', [InvoiceApiController::class, 'statistics'])->name('invoice.statistics');
        Route::get('/by-creator', [InvoiceApiController::class, 'byCreator'])->name('invoice.by-creator');
        Route::get('/by-project-category', [InvoiceApiController::class, 'byProjectCategory'])->name('invoice.by-project-category');
        Route::get('/by-month', [InvoiceApiController::class, 'byMonth'])->name('invoice.by-month');
        Route::get('/list', [InvoiceApiController::class, 'list'])->name('invoice.list');
    });

Route::middleware([
    'auth:sanctum',
    'api.auth',
    'api.token.expiry',
    'api.permission:pm.client.read',
    'throttle:api',
    'pm.api.logger',
])
    ->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/list', [ClientApiController::class, 'list'])->name('client.list');
            Route::get('/{id}', [ClientApiController::class, 'show'])->name('client.show');
        });

        Route::prefix('client-category')->group(function () {
            Route::get('/list', [ClientCategoryApiController::class, 'list'])->name('client-category.list');
        });

        Route::prefix('service')->group(function () {
            Route::get('/list', [ServiceApiController::class, 'list'])->name('service.list');
        });
    });
