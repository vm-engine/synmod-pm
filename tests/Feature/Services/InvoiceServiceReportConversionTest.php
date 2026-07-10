<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Services\InvoiceService;

uses(RefreshDatabase::class);

/**
 * One IDR invoice (total_amount = 500000, passthrough) and one USD invoice
 * (total_amount = 100, exchange_rate = 16000, total_amount_idr = 1600000
 * pre-computed as if InvoiceConversionService had already run). Combined
 * converted total must be 2,100,000 — NOT 500,100 (the raw sum), which is
 * the meaningful regression case since raw-vs-converted sums must differ.
 */
function seedMixedCurrencyInvoices(): void
{
    ProjectInvoice::factory()->create([
        'currency' => 'IDR',
        'archived' => false,
        'is_paid' => false,
        'amount' => 500000,
        'vat_amount' => 0,
        'total_amount' => 500000,
        'exchange_rate' => 1,
        'amount_idr' => 500000,
        'vat_amount_idr' => 0,
        'total_amount_idr' => 500000,
    ]);

    ProjectInvoice::factory()->create([
        'currency' => 'USD',
        'archived' => false,
        'is_paid' => false,
        'amount' => 100,
        'vat_amount' => 0,
        'total_amount' => 100,
        'exchange_rate' => 16000,
        'amount_idr' => 1600000,
        'vat_amount_idr' => 0,
        'total_amount_idr' => 1600000,
    ]);
}

it('sums converted total_amount_idr in statistics(), not raw total_amount', function () {
    seedMixedCurrencyInvoices();

    $stats = app(InvoiceService::class)->statistics((int) now()->year);

    expect((float) $stats['total_invoiced'])->toBe(2100000.0)
        ->and((float) $stats['total_invoiced'])->not->toBe(500100.0);
});

it('sums converted total_amount_idr in getByCreator()', function () {
    seedMixedCurrencyInvoices();

    $rows = app(InvoiceService::class)->getByCreator((int) now()->year);

    expect((float) $rows->sum('total_amount'))->toBe(2100000.0);
});

it('sums converted amount_idr and total_amount_idr in getByProject()', function () {
    seedMixedCurrencyInvoices();

    $rows = app(InvoiceService::class)->getByProject((int) now()->year);

    expect((float) $rows->sum('total_amount'))->toBe(2100000.0)
        ->and((float) $rows->sum('sum_amount'))->toBe(2100000.0);
});

it('sums converted values in apiStatistics()', function () {
    seedMixedCurrencyInvoices();

    $stats = app(InvoiceService::class)->apiStatistics((int) now()->year);

    expect($stats['grand_total_amount'])->toBe(2100000.0);
});

it('sums converted values in getByMonth()', function () {
    seedMixedCurrencyInvoices();

    $rows = app(InvoiceService::class)->getByMonth((int) now()->year);

    expect((float) $rows->sum('total_amount'))->toBe(2100000.0);
});

it('leaves getInvoiceList() returning raw native-currency total_amount, unconverted', function () {
    seedMixedCurrencyInvoices();

    $list = app(InvoiceService::class)->getInvoiceList(['year' => (int) now()->year, 'limit' => 20]);

    $totals = collect($list->items())->pluck('total_amount')->map(fn ($v) => (float) $v)->sort()->values();

    expect($totals->all())->toBe([100.0, 500000.0]);
});
