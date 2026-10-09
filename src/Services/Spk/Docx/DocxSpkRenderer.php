<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Spk\Docx;

use RuntimeException;
use VmEngine\Pm\Services\Sph\Docx\DocxSphRenderer;
use VmEngine\Pm\Services\Spk\SpkDocumentData;
use ZipArchive;

/**
 * Word version of the SPK on the SPH template (styles, page setup, letterhead header/footer);
 * the body comes from the pm::docx.spk.document view.
 */
class DocxSpkRenderer
{
    private const SIGNATURE_WIDTH_EMU = 1828800;

    /** Letterhead widths as laid out in the template header/footer (see DocxSphRenderer). */
    private const HEADER_WIDTH_EMU = 7648575;

    private const FOOTER_WIDTH_EMU = 7553325;

    public function render(SpkDocumentData $data, string $target): void
    {
        if (! copy(DocxSphRenderer::templatePath(), $target)) {
            throw new RuntimeException('Cannot copy the SPK template to '.$target);
        }
        $zip = new ZipArchive;
        if ($zip->open($target) !== true) {
            throw new RuntimeException('Cannot open '.$target);
        }

        /** @var array<string, string> $relations rId => media target */
        $relations = [];

        $signature = null;
        if ($data->signaturePath !== null) {
            [$bytes, $extension, $width, $height] = DocxSphRenderer::image((string) file_get_contents($data->signaturePath));
            $signature = [
                'rid' => DocxSphRenderer::addMedia($zip, $relations, $bytes, $extension),
                'cx' => self::SIGNATURE_WIDTH_EMU,
                'cy' => (int) round(self::SIGNATURE_WIDTH_EMU * $height / $width),
            ];
        }

        $attachmentImages = [];
        foreach ($data->attachments as $i => $attachment) {
            [$bytes, $extension, $width, $height] = DocxSphRenderer::image((string) file_get_contents($attachment['path']));
            $attachmentImages[$i] = ['rid' => DocxSphRenderer::addMedia($zip, $relations, $bytes, $extension)] + DocxSphRenderer::fit($width, $height);
        }

        $body = view('pm::docx.spk.document', ['data' => $data, 'signature' => $signature, 'attachmentImages' => $attachmentImages])->render();
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n".trim($body));

        $extra = '';
        foreach ($relations as $rid => $mediaTarget) {
            $extra .= '<Relationship Id="'.$rid.'" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="'.$mediaTarget.'"/>';
        }
        $rels = (string) $zip->getFromName('word/_rels/document.xml.rels');
        $zip->addFromString('word/_rels/document.xml.rels', str_replace('</Relationships>', $extra.'</Relationships>', $rels));

        DocxSphRenderer::letterhead($zip, 'header1', $data->headerImagePath, self::HEADER_WIDTH_EMU);
        DocxSphRenderer::letterhead($zip, 'footer1', $data->footerImagePath, self::FOOTER_WIDTH_EMU);
        DocxSphRenderer::ensureContentTypes($zip, ['png' => 'image/png', 'jpeg' => 'image/jpeg']);

        $zip->close();
    }
}
