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

it('hydrates exchangeRate from the invoice when loaded', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'USD', 'exchange_rate' => 16250]);

    $component = Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id);

    expect($component->get('exchangeRate'))->toBe(16250.0);
});

it('recalculates and persists total_amount_idr when exchangeRate changes on a USD invoice', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'exchange_rate' => 16000, 'is_paid' => false,
        'include_discount' => false, 'discount_percentage' => 0, 'discount_amount' => 0,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '100')
        ->call('storeItem')
        ->set('exchangeRate', 16500);

    $invoice->refresh();
    expect((float) $invoice->exchange_rate)->toBe(16500.0);
    expect((float) $invoice->total_amount)->toBe(100.0);
    expect((float) $invoice->total_amount_idr)->toBe(1650000.0);
    expect((float) $invoice->amount_idr)->toBe(1650000.0);
});

it('always keeps _idr fields equal to raw fields for IDR invoices regardless of exchangeRate', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR']);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '1.500.000')
        ->call('storeItem');

    $invoice->refresh();
    expect((float) $invoice->exchange_rate)->toBe(1.0);
    expect((float) $invoice->total_amount_idr)->toBe((float) $invoice->total_amount);
});

it('locks the exchange rate once the invoice is paid, ignoring stray in-memory changes', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'exchange_rate' => 16000, 'is_paid' => true,
        'include_discount' => false, 'discount_percentage' => 0, 'discount_amount' => 0,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('exchangeRate', 99999)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '100')
        ->call('storeItem');

    expect((float) $invoice->fresh()->exchange_rate)->toBe(16000.0);
});

it('hydrates exchangeRateInput as an IDR-formatted currency string when loaded', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'USD', 'exchange_rate' => 16250]);

    $component = Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id);

    expect($component->get('exchangeRateInput'))->toBe('16.250');
});

it('parses a masked exchangeRateInput string into exchangeRate and persists the conversion', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'exchange_rate' => 16000, 'is_paid' => false,
        'include_discount' => false, 'discount_percentage' => 0, 'discount_amount' => 0,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->set('newItemName', 'Service')
        ->set('newItemAmount', '100')
        ->call('storeItem')
        ->set('exchangeRateInput', '16.500');

    $invoice->refresh();
    expect((float) $invoice->exchange_rate)->toBe(16500.0);
    expect((float) $invoice->total_amount_idr)->toBe(1650000.0);
});
