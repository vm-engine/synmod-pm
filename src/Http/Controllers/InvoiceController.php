<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Models\ProjectInvoice;

class InvoiceController extends Controller
{
    public function download(int $id): BinaryFileResponse|Response
    {
        $invoice = ProjectInvoice::with(['invoiceDocument'])->findOrFail($id);

        if (! $invoice->invoice_document_id || ! $invoice->invoiceDocument) {
            abort(404, __('pm::invoice.pdf_not_found'));
        }

        $filePath = Storage::disk('public')->path($invoice->invoiceDocument->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::invoice.pdf_not_found'));
        }

        $fileName = $invoice->invoiceDocument->file_info['name']
            ?? basename($invoice->invoiceDocument->file_path);

        return response()->download(
            $filePath,
            $fileName,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function print(int $id)
    {
        $invoice = ProjectInvoice::with([
            'project',
            'issuer',
            'bankAccount',
            'signature',
            'items',
            'client',
            'spkDocument',
        ])->findOrFail($id);

        return view('pm::pdf.invoice', [
            'invoice' => $invoice,
            'forBrowser' => true,
        ]);
    }

    public function regenerate(int $id): Response
    {
        $invoice = ProjectInvoice::with([
            'project',
            'issuer',
            'bankAccount',
            'signature',
            'items',
            'client',
            'spkDocument',
        ])->findOrFail($id);

        // This will regenerate the PDF on the fly without saving
        $pdf = Pdf::loadView('pm::pdf.invoice', [
            'invoice' => $invoice,
        ]);

        $pdf->setPaper('a4');

        return $pdf->stream('invoice_'.$invoice->invoice_number.'.pdf');
    }
}
