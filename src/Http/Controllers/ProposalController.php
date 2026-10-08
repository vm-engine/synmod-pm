<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Services\ProjectDocumentService;
use VmEngine\Pm\Services\Proposal\PdfProposalRenderer;
use VmEngine\Pm\Services\Proposal\Pptx\PptxProposalRenderer;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;
use VmEngine\Pm\Services\Proposal\ProposalValidator;

class ProposalController extends Controller
{
    public function preview(int $id, string $format, ProposalValidator $validator): Response|BinaryFileResponse
    {
        $proposal = Project::query()->findOrFail($id)->proposal;
        abort_if($proposal === null, 404);
        $errors = $validator->errors($proposal);
        abort_if($errors !== [], 422, implode(' ', array_merge(...array_values($errors))));

        $data = ProposalDocumentData::build($proposal);
        $name = 'proposal-preview-'.Str::slug($proposal->subject !== '' ? $proposal->subject : (string) $proposal->project_id);

        if ($format === 'pdf') {
            return response(app(PdfProposalRenderer::class)->render($data), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$name.'.pdf"',
                'Cache-Control' => 'no-store',
            ]);
        }

        $temp = tempnam(sys_get_temp_dir(), 'preview').'.pptx';
        app(PptxProposalRenderer::class)->render($data, $temp);

        return response()->download($temp, $name.'.pptx', ['Cache-Control' => 'no-store'])->deleteFileAfterSend();
    }

    public function downloadPptx(int $id, ProjectDocumentService $documents): StreamedResponse
    {
        $document = ProjectDocument::query()->findOrFail($id);
        $document = $documents->validateDocumentAccess($document->project, $document->id);
        $path = $document->file_info['pptx'] ?? null;
        abort_unless(is_string($path) && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, (string) ($document->file_info['pptx_name'] ?? basename($path)));
    }
}
