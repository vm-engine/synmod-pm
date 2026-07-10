<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;

class ReceiptPdfService
{
    /**
     * Generate receipt PDF and create document record
     */
    public function generate(ProjectInvoice $invoice, int $userId): ProjectDocument
    {
        $invoice->load(['items', 'issuer', 'signature', 'project', 'client']);

        // Generate receipt number if not exists
        $receiptNumber = $invoice->receipt_number;
        if (! $receiptNumber) {
            $receiptNumber = Documents::RECEIPT->handler()->generateDocumentNumber(
                $invoice->project,
                Documents::RECEIPT
            );
        }

        // Amount-in-words reflects the invoice's native currency (e.g. "One
        // Hundred Dollars" for a USD invoice), not a forced IDR conversion.
        // The stored receipt_amount_text is the Indonesian version, since
        // that's what other read paths (print, modal preview) fall back to.
        $cf = app(CurrencyFormatter::class);
        $currency = $invoice->currency ?? 'IDR';
        $currencyName = $cf->currencyName($currency);
        $currencyNamePlural = $cf->currencyNamePlural($currency);

        $amountTextId = Terbilang::amountInWords((float) $invoice->total_amount, $currencyName, $currencyNamePlural, 'id');

        // Update invoice with receipt data
        $invoice->update([
            'receipt_number' => $receiptNumber,
            'receipt_amount_text' => $amountTextId,
        ]);

        // Increment receipt index
        Documents::RECEIPT->handler()->incrementIndex($invoice->project, Documents::RECEIPT);

        $storagePath = 'projects/'.$invoice->project_id.'/documents/'.Documents::RECEIPT->code();

        // Language => invoice column holding that language's document id.
        $langs = [
            'id' => 'receipt_document_id',
            'en' => 'receipt_document_en_id',
        ];

        $originalLocale = app()->getLocale();
        $primary = null;

        foreach ($langs as $lang => $column) {
            app()->setLocale($lang);

            $amountText = Terbilang::amountInWords((float) $invoice->total_amount, $currencyName, $currencyNamePlural, $lang);

            $pdf = Pdf::loadView('pm::pdf.receipt', [
                'invoice' => $invoice,
                'receiptNumber' => $receiptNumber,
                'amountText' => $amountText,
            ]);
            $pdf->setPaper('a4');

            $filename = 'receipt_'.str_replace(['/', '\\', ' '], '_', $receiptNumber).'_'.$lang.'.pdf';

            // Delete the previous document for this language, if any.
            if ($invoice->{$column}) {
                $oldDocument = ProjectDocument::find($invoice->{$column});
                if ($oldDocument && $oldDocument->file_path) {
                    Storage::delete($oldDocument->file_path);
                    $oldDocument->delete();
                }
            }

            $fullPath = $storagePath.'/'.$filename;
            Storage::put($fullPath, $pdf->output());

            $document = ProjectDocument::create([
                'user_id' => $userId,
                'project_id' => $invoice->project_id,
                'client_id' => $invoice->client_id,
                'type' => Documents::RECEIPT->value,
                'number' => $receiptNumber,
                'notes' => 'Receipt '.$receiptNumber.' ('.strtoupper($lang).') - Auto-generated',
                'file_path' => $fullPath,
                'file_info' => [
                    'name' => $filename,
                    'size' => Storage::size($fullPath),
                    'mime' => 'application/pdf',
                ],
                'version' => 1,
                'created_by' => $userId,
            ]);

            $invoice->update([$column => $document->id]);

            if ($lang === 'id') {
                $primary = $document;
            }
        }

        app()->setLocale($originalLocale);

        return $primary;
    }
}
