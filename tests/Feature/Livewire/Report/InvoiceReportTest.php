<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use VmEngine\Pm\Models\ProjectInvoice;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('lists invoices of a soft-deleted creator under their name', function () {
    $UserModel = config('auth.providers.users.model');
    $creator = $UserModel::factory()->create(['name' => 'Trashed Creator']);
    ProjectInvoice::factory()->create(['issue_date' => now(), 'created_by' => $creator->id]);
    $creator->delete();

    Livewire::test('pm::report.invoice')
        ->assertStatus(200)
        ->assertSee('Trashed Creator');
});

test('renders when an invoice references a creator id with no user row', function () {
    // Orphaned created_by ids only exist in data imported without FK checks.
    // Defer the SQLite FK check to commit; RefreshDatabase never commits.
    DB::statement('PRAGMA defer_foreign_keys = ON');
    ProjectInvoice::factory()->create(['issue_date' => now(), 'created_by' => 999999]);

    Livewire::test('pm::report.invoice')
        ->assertStatus(200)
        ->assertSee(__('pm::report.invoice.unknown_creator'));
});
