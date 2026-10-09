<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;

/**
 * A4 PDF of the SPK (dompdf). Same content and order as DocxSpkRenderer.
 */
class PdfSpkRenderer
{
    public function render(SpkDocumentData $data): string
    {
        // dompdf's font cache must be writable by the web server too (see PdfProposalRenderer)
        File::ensureDirectoryExists(storage_path('fonts'), 0775);

        $pdf = Pdf::loadView('pm::pdf.spk.document', ['data' => $data]);
        $pdf->setPaper('a4');
        $pdf->setOption('chroot', [base_path(), TemplateMap::packageRoot(), storage_path()]);

        return $pdf->output();
    }
}
