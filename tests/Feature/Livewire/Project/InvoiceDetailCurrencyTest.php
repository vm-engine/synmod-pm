<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\ProjectInvoice;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

it('forces VAT and DPP off for a non-taxable (USD) currency on recalculation', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'include_vat' => true, 'include_dpp' => true,
        'include_discount' => false, 'discount_percentage' => 0, 'discount_amount' => 0,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '1000.50')
        ->call('storeItem');

    $invoice->refresh();
    expect((bool) $invoice->include_vat)->toBeFalse();
    expect((bool) $invoice->include_dpp)->toBeFalse();
    expect((float) $invoice->amount)->toBe(1000.50);
    expect((float) $invoice->total_amount)->toBe(1000.50);
});

it('parses IDR item amount with dot thousands into an integer-valued decimal', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR']);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '1.500.000')
        ->call('storeItem');

    expect((float) $invoice->fresh()->items()->first()->amount)->toBe(1500000.0);
});
