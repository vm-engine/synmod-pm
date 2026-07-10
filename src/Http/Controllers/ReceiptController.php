<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Services\CurrencyFormatter;

class ReceiptController extends Controller
{
    /**
     * Resolve the requested language, defaulting to Indonesian.
     */
    private function resolveLang(Request $request): string
    {
        return $request->query('lang') === 'en' ? 'en' : 'id';
    }

    /**
     * Download the receipt PDF.
     */
    public function download(Request $request, int $id): BinaryFileResponse|Response
    {
        $lang = $this->resolveLang($request);
        $invoice = ProjectInvoice::with(['receiptDocument', 'receiptDocumentEn'])->findOrFail($id);

        $document = $lang === 'en'
            ? ($invoice->receiptDocumentEn ?? $invoice->receiptDocument)
            : ($invoice->receiptDocument ?? $invoice->receiptDocumentEn);

        if (! $document) {
            abort(404, __('pm::receipt.pdf_not_found'));
        }

        $filePath = Storage::path($document->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::receipt.pdf_not_found'));
        }

        $fileName = $document->file_info['name']
            ?? 'receipt_'.str_replace(['/', '\\', ' '], '_', $invoice->receipt_number).'.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Print the receipt (display view directly).
     */
    public function print(Request $request, int $id): View
    {
        $lang = $this->resolveLang($request);
        app()->setLocale($lang);

        $invoice = ProjectInvoice::with([
            'project',
            'project.client',
            'issuer',
            'signature',
            'items',
            'client',
        ])->findOrFail($id);

        // Get receipt number and amount text
        $receiptNumber = $invoice->receipt_number ?? '-';
        // Amount-in-words reflects the invoice's native currency (e.g. "One
        // Hundred Dollars" for a USD invoice). The stored receipt_amount_text
        // is Indonesian-only, so it can only be reused for the 'id' language.
        if ($lang === 'id' && $invoice->receipt_amount_text) {
            $amountText = $invoice->receipt_amount_text;
        } else {
            $cf = app(CurrencyFormatter::class);
            $currency = $invoice->currency ?? 'IDR';

            $amountText = Terbilang::amountInWords(
                (float) $invoice->total_amount,
                $cf->currencyName($currency),
                $cf->currencyNamePlural($currency),
                $lang
            );
        }

        return view('pm::pdf.receipt', [
            'invoice' => $invoice,
            'receiptNumber' => $receiptNumber,
            'amountText' => $amountText,
            'forBrowser' => true,
        ]);
    }

    /**
     * View the payment proof file.
     */
    public function paymentProof(int $id): BinaryFileResponse|Response
    {
        $invoice = ProjectInvoice::findOrFail($id);

        if (! $invoice->payment_proof) {
            abort(404, __('pm::receipt.payment_proof_not_found'));
        }

        $filePath = Storage::path($invoice->payment_proof);

        if (! file_exists($filePath)) {
            abort(404, __('pm::receipt.payment_proof_not_found'));
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }
}
