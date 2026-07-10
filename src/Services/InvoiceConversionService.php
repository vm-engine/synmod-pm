<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

class InvoiceConversionService
{
    /**
     * Resolve the default exchange rate for a currency, used to prefill new invoices.
     * IDR is always 1.0. Other currencies read a per-currency dbconf key with a
     * hardcoded fallback so invoice creation never blocks on missing config.
     */
    public function defaultExchangeRate(string $currency): float
    {
        $currency = strtoupper($currency);

        if ($currency === 'IDR') {
            return 1.0;
        }

        $fallback = match ($currency) {
            'USD' => 16000.0,
            default => 1.0,
        };

        return (float) dbconf("pm.invoice.exchange_rate.{$currency}", $fallback);
    }

    /**
     * Normalize the effective exchange rate to persist for an invoice.
     * IDR invoices are always forced to 1.0 regardless of input.
     */
    public function normalizeRate(string $currency, float $exchangeRate): float
    {
        return strtoupper($currency) === 'IDR' ? 1.0 : $exchangeRate;
    }

    /**
     * Convert a money breakdown into its IDR-equivalent counterparts.
     *
     * @param  array{amount: float, vat_amount: float, dpp_amount: float, discount_amount: float, total_amount: float}  $amounts
     * @return array{amount_idr: float, vat_amount_idr: float, dpp_amount_idr: float, discount_amount_idr: float, total_amount_idr: float}
     */
    public function convert(string $currency, float $exchangeRate, array $amounts): array
    {
        // Defensive: IDR invoices are always 1:1 regardless of what rate was
        // passed in, so a stray non-1 value can never corrupt IDR invoices.
        $rate = $this->normalizeRate($currency, $exchangeRate);

        return [
            'amount_idr' => round($amounts['amount'] * $rate, 2),
            'vat_amount_idr' => round($amounts['vat_amount'] * $rate, 2),
            'dpp_amount_idr' => round($amounts['dpp_amount'] * $rate, 2),
            'discount_amount_idr' => round($amounts['discount_amount'] * $rate, 2),
            'total_amount_idr' => round($amounts['total_amount'] * $rate, 2),
        ];
    }
}
