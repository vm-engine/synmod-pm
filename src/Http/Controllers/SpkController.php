<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\Spk\Docx\DocxSpkRenderer;
use VmEngine\Pm\Services\Spk\PdfSpkRenderer;
use VmEngine\Pm\Services\Spk\SpkDocumentData;
use VmEngine\Pm\Services\Spk\SpkValidator;

class SpkController extends Controller
{
    public function preview(int $id, string $format, SpkValidator $validator): Response|BinaryFileResponse
    {
        $spk = Project::query()->findOrFail($id)->spk;
        abort_if($spk === null, 404);
        $errors = $validator->errors($spk);
        abort_if($errors !== [], 422, implode(' ', array_merge(...array_values($errors))));

        $data = SpkDocumentData::build($spk);
        $name = 'spk-preview-'.Str::slug($spk->party1_company !== '' ? $spk->party1_company : (string) $spk->project_id);

        if ($format === 'pdf') {
            return response(app(PdfSpkRenderer::class)->render($data), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$name.'.pdf"',
                'Cache-Control' => 'no-store',
            ]);
        }

        $base = (string) tempnam(sys_get_temp_dir(), 'spk');
        @unlink($base);
        $temp = $base.'.docx';
        app(DocxSpkRenderer::class)->render($data, $temp);

        return response()->download($temp, $name.'.docx', ['Cache-Control' => 'no-store'])->deleteFileAfterSend();
    }
}
