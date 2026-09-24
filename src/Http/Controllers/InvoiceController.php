<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Models\ProjectInvoice;

class InvoiceController extends Controller
{
    /**
     * Resolve the requested language, defaulting to Indonesian.
     */
    private function resolveLang(Request $request): string
    {
        return $request->query('lang') === 'en' ? 'en' : 'id';
    }

    public function download(Request $request, int $id): BinaryFileResponse|Response
    {
        $lang = $this->resolveLang($request);
        $invoice = ProjectInvoice::with(['invoiceDocument', 'invoiceDocumentEn'])->findOrFail($id);

        $document = $lang === 'en'
            ? ($invoice->invoiceDocumentEn ?? $invoice->invoiceDocument)
            : ($invoice->invoiceDocument ?? $invoice->invoiceDocumentEn);

        if (! $document) {
            abort(404, __('pm::invoice.pdf_not_found'));
        }

        $filePath = Storage::path($document->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::invoice.pdf_not_found'));
        }

        $fileName = $document->file_info['name'] ?? basename($document->file_path);

        return response()->download(
            $filePath,
            $fileName,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function print(Request $request, int $id): View
    {
        app()->setLocale($this->resolveLang($request));

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
