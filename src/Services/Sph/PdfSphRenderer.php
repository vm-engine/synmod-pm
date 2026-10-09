<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use VmEngine\Pm\Services\Proposal\Gantt\GanttSvgRenderer;
use VmEngine\Pm\Services\Proposal\PdfProposalRenderer;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;

/**
 * A4 PDF of the SPH (dompdf). Same content and order as DocxSphRenderer.
 */
class PdfSphRenderer
{
    public function __construct(private readonly GanttSvgRenderer $gantt) {}

    public function render(SphDocumentData $data): string
    {
        // dompdf's font cache must be writable by the web server too (see PdfProposalRenderer)
        File::ensureDirectoryExists(storage_path('fonts'), 0775);

        $ganttImages = [];
        if ($data->timeplan !== null) {
            foreach (array_keys($data->timeplan['gantt']->pages) as $page) {
                $ganttImages[] = GanttSvgRenderer::toDataUri($this->gantt->render($data->timeplan['gantt'], $page, PdfProposalRenderer::ARROWS));
            }
        }

        $pdf = Pdf::loadView('pm::pdf.sph.document', ['data' => $data, 'ganttImages' => $ganttImages]);
        $pdf->setPaper('a4');
        $pdf->setOption('chroot', [base_path(), TemplateMap::packageRoot(), storage_path()]);

        return $pdf->output();
    }
}
