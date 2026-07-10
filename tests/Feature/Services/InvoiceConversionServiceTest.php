<?php

declare(strict_types=1);

use VmEngine\Pm\Services\InvoiceConversionService;

it('returns 1.0 as the default exchange rate for IDR', function () {
    expect(app(InvoiceConversionService::class)->defaultExchangeRate('IDR'))->toBe(1.0);
});

it('reads the default exchange rate for a non-IDR currency from dbconf', function () {
    dbconf(['pm.invoice.exchange_rate.USD' => 15750]);

    expect(app(InvoiceConversionService::class)->defaultExchangeRate('USD'))->toBe(15750.0);
});

it('falls back to a hardcoded default when no dbconf value is configured for USD', function () {
    expect(app(InvoiceConversionService::class)->defaultExchangeRate('USD'))->toBe(16000.0);
});

it('normalizes IDR to always be rate 1.0 regardless of input', function () {
    expect(app(InvoiceConversionService::class)->normalizeRate('IDR', 12345.0))->toBe(1.0);
});

it('normalizes non-IDR currencies to the given rate', function () {
    expect(app(InvoiceConversionService::class)->normalizeRate('USD', 16000.0))->toBe(16000.0);
});

it('converts IDR amounts as a passthrough regardless of the rate passed in', function () {
    $amounts = [
        'amount' => 1000000.0,
        'vat_amount' => 110000.0,
        'dpp_amount' => 916666.67,
        'discount_amount' => 50000.0,
        'total_amount' => 1060000.0,
    ];

    // Deliberately wrong rate — must be ignored for IDR.
    $result = app(InvoiceConversionService::class)->convert('IDR', 16000.0, $amounts);

    expect($result)->toBe([
        'amount_idr' => 1000000.0,
        'vat_amount_idr' => 110000.0,
        'dpp_amount_idr' => 916666.67,
        'discount_amount_idr' => 50000.0,
        'total_amount_idr' => 1060000.0,
    ]);
});

it('converts USD amounts by multiplying by the exchange rate, rounded to 2 decimals', function () {
    $result = app(InvoiceConversionService::class)->convert('USD', 16000.0, [
        'amount' => 100.0,
        'vat_amount' => 0.0,
        'dpp_amount' => 0.0,
        'discount_amount' => 0.0,
        'total_amount' => 100.0,
    ]);

    expect($result)->toBe([
        'amount_idr' => 1600000.0,
        'vat_amount_idr' => 0.0,
        'dpp_amount_idr' => 0.0,
        'discount_amount_idr' => 0.0,
        'total_amount_idr' => 1600000.0,
    ]);
});
