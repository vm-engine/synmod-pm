<?php

declare(strict_types=1);

namespace VmEngine\Pm\Console\Commands;

use Illuminate\Console\Command;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Services\InvoiceConversionService;

class RecalculateInvoiceConversionsCommand extends Command
{
    protected $signature = 'mod-pm:recalculate-invoice-conversions
                            {--dry-run : Show what would change without saving}
                            {--rate= : Uniform historical IDR exchange rate applied to all non-IDR invoices in this run}
                            {--use-saved-rate : Recompute _idr fields from the exchange_rate already stored on each invoice, instead of a uniform --rate}
                            {--missing-only : Only process invoices whose _idr fields have not been populated yet}
                            {--year= : Optionally limit to invoices issued in a specific year}';

    protected $description = 'Backfill/resync exchange_rate and IDR-converted amount fields on existing invoices';

    public function handle(InvoiceConversionService $conversion): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $rateOption = $this->option('rate');
        $useSavedRate = (bool) $this->option('use-saved-rate');
        $missingOnly = (bool) $this->option('missing-only');
        $year = $this->option('year') ? (int) $this->option('year') : null;

        if ($useSavedRate && $rateOption !== null && $rateOption !== '') {
            $this->error('--rate and --use-saved-rate are mutually exclusive. Pass one or the other.');

            return self::FAILURE;
        }

        $query = ProjectInvoice::query();
        if ($year !== null) {
            $query->whereYear('issue_date', $year);
        }
        if ($missingOnly) {
            // "Missing" = a real total exists but its IDR counterpart was
            // never computed — legacy rows that predate this feature.
            // Legitimately-empty invoices (total_amount = 0, e.g. no items
            // yet) are correctly excluded since 0 * any rate = 0 either way.
            $query->where('total_amount', '!=', 0)->where('total_amount_idr', 0);
        }

        $hasNonIdr = (clone $query)->where('currency', '!=', 'IDR')->exists();

        // Fail fast: bulk update, so refuse to run rather than silently
        // skipping non-IDR rows or guessing a rate.
        if ($hasNonIdr && ! $useSavedRate) {
            if ($rateOption === null || $rateOption === '') {
                $this->error('Non-IDR invoices exist. Supply --rate=<positive number>, or pass --use-saved-rate to resync from the existing rate on each invoice. Aborting — no changes made.');

                return self::FAILURE;
            }

            if (! is_numeric($rateOption) || (float) $rateOption <= 0) {
                $this->error('--rate must be a positive number.');

                return self::FAILURE;
            }
        }

        $rate = $rateOption !== null && $rateOption !== '' ? (float) $rateOption : null;

        $total = (clone $query)->count();
        if ($total === 0) {
            $this->info('No invoices found matching the given filters.');

            return self::SUCCESS;
        }

        if ($isDryRun) {
            $this->info('Running in DRY RUN mode — no records will be saved.');
        }

        $idrCount = 0;
        $convertedCount = 0;
        $errorCount = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $query->chunkById(200, function ($invoices) use (&$idrCount, &$convertedCount, &$errorCount, $conversion, $rate, $useSavedRate, $isDryRun, $bar) {
            foreach ($invoices as $invoice) {
                $bar->advance();

                try {
                    $currency = $invoice->currency ?? 'IDR';
                    // --use-saved-rate: keep whatever's already on the row.
                    // Otherwise: uniform --rate for non-IDR, 1.0 for IDR.
                    $sourceRate = $useSavedRate
                        ? (float) $invoice->exchange_rate
                        : (strtoupper($currency) === 'IDR' ? 1.0 : (float) $rate);
                    $effectiveRate = $conversion->normalizeRate($currency, $sourceRate);

                    $idrAmounts = $conversion->convert($currency, $effectiveRate, [
                        'amount' => (float) $invoice->amount,
                        'vat_amount' => (float) $invoice->vat_amount,
                        'dpp_amount' => (float) $invoice->dpp_amount,
                        'discount_amount' => (float) $invoice->discount_amount,
                        'total_amount' => (float) $invoice->total_amount,
                    ]);

                    if (! $isDryRun) {
                        $invoice->update([
                            'exchange_rate' => $effectiveRate,
                            ...$idrAmounts,
                        ]);
                    }

                    strtoupper($currency) === 'IDR' ? $idrCount++ : $convertedCount++;
                } catch (\Throwable $e) {
                    $errorCount++;
                    $this->newLine();
                    $this->error("Error processing invoice #{$invoice->id}: ".$e->getMessage());
                }
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info('Backfill Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['IDR invoices (rate = 1.000000)', $idrCount],
                ['Converted invoices ('.($useSavedRate ? 'own saved rate' : 'rate = '.($rate ?? 'n/a')).')', $convertedCount],
                ['Errors', $errorCount],
                ['Total processed', $total],
            ]
        );

        if ($isDryRun) {
            $this->info('Run without --dry-run to persist these changes.');
        }

        return $errorCount > 0 ? self::FAILURE : self::SUCCESS;
    }
}
