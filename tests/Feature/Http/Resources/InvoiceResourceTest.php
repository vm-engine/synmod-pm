<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use VmEngine\Pm\Http\Resources\InvoiceResource;
use VmEngine\Pm\Models\ProjectInvoice;

uses(RefreshDatabase::class);

it('includes currency, exchange_rate, and all idr-converted fields as floats', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'USD',
        'exchange_rate' => 16000,
        'amount' => 100,
        'amount_idr' => 1600000,
        'vat_amount' => 0,
        'vat_amount_idr' => 0,
        'discount_amount' => 0,
        'discount_amount_idr' => 0,
        'total_amount' => 100,
        'total_amount_idr' => 1600000,
    ]);

    $data = (new InvoiceResource($invoice))->toArray(Request::create('/'));

    expect($data['currency'])->toBe('USD');
    expect($data['exchange_rate'])->toBe(16000.0);
    expect($data['amount'])->toBe(100.0);
    expect($data['amount_idr'])->toBe(1600000.0);
    expect($data['vat_amount_idr'])->toBe(0.0);
    expect($data['discount_amount_idr'])->toBe(0.0);
    expect($data['total_amount'])->toBe(100.0);
    expect($data['total_amount_idr'])->toBe(1600000.0);
});
