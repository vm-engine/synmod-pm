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
     * Generate invoice PDF and create document record
     */
    public function generate(ProjectInvoice $invoice, int $userId): ProjectDocument
    {
        $invoice->load(['items', 'issuer', 'bankAccount', 'signature', 'project', 'client']);

        // Generate PDF
        $pdf = Pdf::loadView('pm::pdf.invoice', [
            'invoice' => $invoice,
        ]);

        $pdf->setPaper('a4');

        // Generate filename
        $filename = 'invoice_'.str_replace(['/', '\\', ' '], '_', $invoice->invoice_number).'.pdf';
        $storagePath = 'projects/'.$invoice->project_id.'/documents/'.Documents::INVOICE->code();

        // Delete old document if exists
        if ($invoice->invoice_document_id) {
            $oldDocument = ProjectDocument::find($invoice->invoice_document_id);
            if ($oldDocument && $oldDocument->file_path) {
                Storage::delete($oldDocument->file_path);
                $oldDocument->delete();
            }
        }

        // Store PDF
        $fullPath = $storagePath.'/'.$filename;
        Storage::put($fullPath, $pdf->output());

        // Create project document record
        $document = ProjectDocument::create([
            'user_id' => $userId,
            'project_id' => $invoice->project_id,
            'client_id' => $invoice->client_id,
            'type' => Documents::INVOICE->value,
            'number' => $invoice->invoice_number,
            'notes' => 'Invoice '.$invoice->invoice_number.' - Auto-generated',
            'file_path' => $fullPath,
            'file_info' => [
                'name' => $filename,
                'size' => Storage::size($fullPath),
                'mime' => 'application/pdf',
            ],
            'version' => 1,
            'created_by' => $userId,
        ]);

        // Update invoice with document reference
        $invoice->update([
            'invoice_document_id' => $document->id,
        ]);

        return $document;
    }
}
