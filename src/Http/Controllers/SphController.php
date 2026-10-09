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
use VmEngine\Pm\Services\Sph\Docx\DocxSphRenderer;
use VmEngine\Pm\Services\Sph\PdfSphRenderer;
use VmEngine\Pm\Services\Sph\SphDocumentData;
use VmEngine\Pm\Services\Sph\SphValidator;

class SphController extends Controller
{
    public function preview(int $id, string $format, SphValidator $validator): Response|BinaryFileResponse
    {
        $sph = Project::query()->findOrFail($id)->sph;
        abort_if($sph === null, 404);
        $errors = $validator->errors($sph);
        abort_if($errors !== [], 422, implode(' ', array_merge(...array_values($errors))));

        $data = SphDocumentData::build($sph);
        $name = 'sph-preview-'.Str::slug($sph->recipient_company !== '' ? $sph->recipient_company : (string) $sph->project_id);

        if ($format === 'pdf') {
            return response(app(PdfSphRenderer::class)->render($data), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$name.'.pdf"',
                'Cache-Control' => 'no-store',
            ]);
        }

        $base = (string) tempnam(sys_get_temp_dir(), 'sph');
        @unlink($base);
        $temp = $base.'.docx';
        app(DocxSphRenderer::class)->render($data, $temp);

        return response()->download($temp, $name.'.docx', ['Cache-Control' => 'no-store'])->deleteFileAfterSend();
    }

    public function downloadDocx(int $id, ProjectDocumentService $documents): StreamedResponse
    {
        $document = ProjectDocument::query()->findOrFail($id);
        $document = $documents->validateDocumentAccess($document->project, $document->id);
        $path = $document->file_info['docx'] ?? null;
        abort_unless(is_string($path) && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path, (string) ($document->file_info['docx_name'] ?? basename($path)));
    }
}
