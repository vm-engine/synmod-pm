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

        // Generate amount text
        $amountText = Terbilang::rupiah((float) $invoice->total_amount);

        // Update invoice with receipt data
        $invoice->update([
            'receipt_number' => $receiptNumber,
            'receipt_amount_text' => $amountText,
        ]);

        // Increment receipt index
        Documents::RECEIPT->handler()->incrementIndex($invoice->project, Documents::RECEIPT);

        // Generate PDF
        $pdf = Pdf::loadView('pm::pdf.receipt', [
            'invoice' => $invoice,
            'receiptNumber' => $receiptNumber,
            'amountText' => $amountText,
        ]);

        $pdf->setPaper('a4');

        // Generate filename
        $filename = 'receipt_'.str_replace(['/', '\\', ' '], '_', $receiptNumber).'.pdf';
        $storagePath = 'projects/'.$invoice->project_id.'/documents/'.Documents::RECEIPT->code();

        // Delete old document if exists
        if ($invoice->receipt_document_id) {
            $oldDocument = ProjectDocument::find($invoice->receipt_document_id);
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
            'type' => Documents::RECEIPT->value,
            'number' => $receiptNumber,
            'notes' => 'Receipt '.$receiptNumber.' - Auto-generated',
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
            'receipt_document_id' => $document->id,
        ]);

        return $document;
    }
}
