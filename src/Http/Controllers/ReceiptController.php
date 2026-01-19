<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\ProjectInvoice;

class ReceiptController extends Controller
{
    /**
     * Download the receipt PDF.
     */
    public function download(int $id): BinaryFileResponse|Response
    {
        $invoice = ProjectInvoice::with(['receiptDocument'])->findOrFail($id);

        if (! $invoice->receipt_document_id || ! $invoice->receiptDocument) {
            abort(404, __('pm::receipt.pdf_not_found'));
        }

        $filePath = Storage::path($invoice->receiptDocument->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::receipt.pdf_not_found'));
        }

        $fileName = $invoice->receiptDocument->file_info['name']
            ?? 'receipt_'.str_replace(['/', '\\', ' '], '_', $invoice->receipt_number).'.pdf';

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Print the receipt (display view directly).
     */
    public function print(int $id): View
    {
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
        $amountText = $invoice->receipt_amount_text ?? Terbilang::rupiah((float) $invoice->total_amount);

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
