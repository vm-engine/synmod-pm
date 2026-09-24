<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;

class InvoicePdfService
{
    /**
     * Generate the invoice PDF in both Indonesian and English, store each as a
     * ProjectDocument, and point the invoice at both language references.
     *
     * Returns the Indonesian (primary/default) document.
     */
    public function generate(ProjectInvoice $invoice, int $userId): ProjectDocument
    {
        $invoice->load(['items', 'issuer', 'bankAccount', 'signature', 'project', 'client', 'spkDocument']);

        // Language => invoice column holding that language's document id.
        $langs = [
            'id' => 'invoice_document_id',
            'en' => 'invoice_document_en_id',
        ];

        $storagePath = 'projects/'.$invoice->project_id.'/documents/'.Documents::INVOICE->code();
        $originalLocale = app()->getLocale();
        $primary = null;

        foreach ($langs as $lang => $column) {
            app()->setLocale($lang);

            $pdf = Pdf::loadView('pm::pdf.invoice', ['invoice' => $invoice]);
            $pdf->setPaper('a4');

            $filename = 'invoice_'.str_replace(['/', '\\', ' '], '_', $invoice->invoice_number).'_'.$lang.'.pdf';

            // Delete the previous document for this language, if any.
            if ($invoice->{$column}) {
                $oldDocument = ProjectDocument::whereKey($invoice->{$column})->first();
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
                'type' => Documents::INVOICE->value,
                'number' => $invoice->invoice_number,
                'notes' => 'Invoice '.$invoice->invoice_number.' ('.strtoupper($lang).') - Auto-generated',
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
