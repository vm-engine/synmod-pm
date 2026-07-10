<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use VmEngine\Pm\Models\ProjectInvoice;

uses(RefreshDatabase::class);

test('succeeds without --rate when only IDR invoices exist', function () {
    $invoice = ProjectInvoice::factory()->create([
        'currency' => 'IDR', 'total_amount' => 500000, 'total_amount_idr' => 0,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions')
        ->assertExitCode(0);

    $invoice->refresh();
    expect((float) $invoice->exchange_rate)->toBe(1.0);
    expect((float) $invoice->total_amount_idr)->toBe(500000.0);
});

test('fails fast with zero writes when non-IDR invoices exist and neither --rate nor --use-saved-rate is given', function () {
    $idr = ProjectInvoice::factory()->create(['currency' => 'IDR', 'total_amount' => 500000, 'total_amount_idr' => 0]);
    $usd = ProjectInvoice::factory()->create(['currency' => 'USD', 'total_amount' => 100, 'total_amount_idr' => 0, 'exchange_rate' => 1]);

    $this->artisan('mod-pm:recalculate-invoice-conversions')
        ->assertExitCode(1);

    expect((float) $idr->fresh()->total_amount_idr)->toBe(0.0);
    expect((float) $usd->fresh()->total_amount_idr)->toBe(0.0);
});

test('fails when --rate and --use-saved-rate are passed together', function () {
    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--rate' => '16000', '--use-saved-rate' => true])
        ->assertExitCode(1);
});

test('applies uniform --rate to non-IDR invoices and rate 1 to IDR invoices', function () {
    $idr = ProjectInvoice::factory()->create([
        'currency' => 'IDR', 'total_amount' => 500000, 'total_amount_idr' => 0,
    ]);
    $usd = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'total_amount' => 100, 'amount' => 100, 'total_amount_idr' => 0, 'exchange_rate' => 1,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--rate' => '16000'])
        ->assertExitCode(0);

    expect((float) $idr->fresh()->exchange_rate)->toBe(1.0);
    expect((float) $idr->fresh()->total_amount_idr)->toBe(500000.0);
    expect((float) $usd->fresh()->exchange_rate)->toBe(16000.0);
    expect((float) $usd->fresh()->total_amount_idr)->toBe(1600000.0);
});

test('rejects zero, negative, and non-numeric --rate with no writes', function () {
    $usd = ProjectInvoice::factory()->create(['currency' => 'USD', 'total_amount' => 100, 'total_amount_idr' => 0, 'exchange_rate' => 1]);

    foreach (['0', '-5', 'abc'] as $badRate) {
        $this->artisan('mod-pm:recalculate-invoice-conversions', ['--rate' => $badRate])
            ->assertExitCode(1);
    }

    expect((float) $usd->fresh()->total_amount_idr)->toBe(0.0);
});

test('--dry-run prints a summary but persists nothing', function () {
    $usd = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'total_amount' => 100, 'total_amount_idr' => 0, 'exchange_rate' => 1,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--rate' => '16000', '--dry-run' => true])
        ->assertExitCode(0);

    expect((float) $usd->fresh()->exchange_rate)->toBe(1.0);
    expect((float) $usd->fresh()->total_amount_idr)->toBe(0.0);
});

test('--year filter only touches invoices issued in that year', function () {
    $inYear = ProjectInvoice::factory()->create([
        'currency' => 'IDR', 'issue_date' => '2025-06-01', 'total_amount' => 500000, 'total_amount_idr' => 0,
    ]);
    $outOfYear = ProjectInvoice::factory()->create([
        'currency' => 'IDR', 'issue_date' => '2024-06-01', 'total_amount' => 700000, 'total_amount_idr' => 0,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--year' => '2025'])
        ->assertExitCode(0);

    expect((float) $inYear->fresh()->total_amount_idr)->toBe(500000.0);
    expect((float) $outOfYear->fresh()->total_amount_idr)->toBe(0.0);
});

test('--use-saved-rate resyncs _idr fields from each invoice own stored exchange_rate without requiring --rate', function () {
    $usd = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'total_amount' => 100, 'amount' => 100,
        'exchange_rate' => 15750, 'total_amount_idr' => 0,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--use-saved-rate' => true])
        ->assertExitCode(0);

    $usd->refresh();
    expect((float) $usd->exchange_rate)->toBe(15750.0);
    expect((float) $usd->total_amount_idr)->toBe(1575000.0);
});

test('--missing-only skips already-converted rows, including manually-corrected rates, leaving them byte-for-byte unchanged', function () {
    $alreadyConverted = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'total_amount' => 100, 'amount' => 100,
        'exchange_rate' => 15900, 'total_amount_idr' => 1590000, 'amount_idr' => 1590000,
    ]);
    $missing = ProjectInvoice::factory()->create([
        'currency' => 'USD', 'total_amount' => 200, 'amount' => 200,
        'exchange_rate' => 1, 'total_amount_idr' => 0, 'amount_idr' => 0,
    ]);

    $this->artisan('mod-pm:recalculate-invoice-conversions', ['--rate' => '16000', '--missing-only' => true])
        ->assertExitCode(0);

    // Untouched: exact same rate and _idr values as before the run.
    expect((float) $alreadyConverted->fresh()->exchange_rate)->toBe(15900.0);
    expect((float) $alreadyConverted->fresh()->total_amount_idr)->toBe(1590000.0);

    // Backfilled with the uniform rate.
    expect((float) $missing->fresh()->exchange_rate)->toBe(16000.0);
    expect((float) $missing->fresh()->total_amount_idr)->toBe(3200000.0);
});
