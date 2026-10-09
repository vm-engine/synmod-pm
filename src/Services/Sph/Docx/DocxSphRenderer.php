<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph\Docx;

use Imagick;
use ImagickPixel;
use RuntimeException;
use Throwable;
use VmEngine\Pm\Services\Proposal\Gantt\GanttSvgRenderer;
use VmEngine\Pm\Services\Proposal\PdfProposalRenderer;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;
use VmEngine\Pm\Services\Sph\SphDocumentData;
use ZipArchive;

/**
 * Word version of the SPH: the template (made from the CAA Telco SPH) supplies styles, page setup and
 * the letterhead header/footer; the body comes from the pm::docx.sph.document view.
 */
class DocxSphRenderer
{
    /** A4 with 0.75in side margins: 9746 twips. */
    public const CONTENT_WIDTH_EMU = 6188710;

    private const PAGE_HEIGHT_EMU = 10692130;

    /** Attachments taller than this are shrunk so the Lampiran title fits on the page (8in). */
    private const MAX_IMAGE_HEIGHT_EMU = 7315200;

    private const SIGNATURE_WIDTH_EMU = 1828800;

    /** Letterhead widths as laid out in the template header/footer (full page width plus bleed). */
    private const HEADER_WIDTH_EMU = 7648575;

    private const FOOTER_WIDTH_EMU = 7553325;

    private const PIXEL_EMU = 9525;

    /** 1×1 transparent PNG. */
    private const BLANK_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    private const IMAGE_RELATIONSHIP = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image';

    public function __construct(private readonly GanttSvgRenderer $gantt) {}

    public static function templatePath(): string
    {
        return TemplateMap::packageRoot().'/resources/templates/sph/sph.docx';
    }

    public function render(SphDocumentData $data, string $target): void
    {
        if (! copy(self::templatePath(), $target)) {
            throw new RuntimeException('Cannot copy the SPH template to '.$target);
        }
        $zip = new ZipArchive;
        if ($zip->open($target) !== true) {
            throw new RuntimeException('Cannot open '.$target);
        }

        /** @var array<string, string> $relations rId => media target */
        $relations = [];

        $signature = null;
        if ($data->signaturePath !== null) {
            [$bytes, $extension, $width, $height] = self::image((string) file_get_contents($data->signaturePath));
            $signature = [
                'rid' => self::addMedia($zip, $relations, $bytes, $extension),
                'cx' => self::SIGNATURE_WIDTH_EMU,
                'cy' => (int) round(self::SIGNATURE_WIDTH_EMU * $height / $width),
            ];
        }

        $ganttImages = [];
        if ($data->timeplan !== null) {
            foreach (array_keys($data->timeplan['gantt']->pages) as $page) {
                $svg = $this->gantt->render($data->timeplan['gantt'], $page, PdfProposalRenderer::ARROWS);
                [$width, $height] = self::svgSize($svg);
                $ganttImages[] = [
                    'rid' => self::addMedia($zip, $relations, self::svgFallback($svg), 'png'),
                    'svgRid' => self::addMedia($zip, $relations, $svg, 'svg'),
                ] + self::fit($width, $height);
            }
        }

        $attachmentImages = [];
        foreach ($data->attachments as $i => $attachment) {
            [$bytes, $extension, $width, $height] = self::image((string) file_get_contents($attachment['path']));
            $attachmentImages[$i] = ['rid' => self::addMedia($zip, $relations, $bytes, $extension)] + self::fit($width, $height);
        }

        $body = view('pm::docx.sph.document', [
            'data' => $data,
            'signature' => $signature,
            'ganttImages' => $ganttImages,
            'attachmentImages' => $attachmentImages,
        ])->render();
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n".trim($body));

        $extra = '';
        foreach ($relations as $rid => $mediaTarget) {
            $extra .= '<Relationship Id="'.$rid.'" Type="'.self::IMAGE_RELATIONSHIP.'" Target="'.$mediaTarget.'"/>';
        }
        $rels = (string) $zip->getFromName('word/_rels/document.xml.rels');
        $zip->addFromString('word/_rels/document.xml.rels', str_replace('</Relationships>', $extra.'</Relationships>', $rels));

        self::letterhead($zip, 'header1', $data->headerImagePath, self::HEADER_WIDTH_EMU);
        self::letterhead($zip, 'footer1', $data->footerImagePath, self::FOOTER_WIDTH_EMU);
        self::ensureContentTypes($zip, ['png' => 'image/png', 'jpeg' => 'image/jpeg', 'svg' => 'image/svg+xml']);

        $zip->close();
    }

    /**
     * Bytes Word can show: JPEG and PNG pass through, anything else (WebP, GIF) becomes PNG.
     *
     * @return array{0: string, 1: 'png'|'jpeg', 2: int, 3: int} bytes, extension, width, height in pixels
     */
    public static function image(string $bytes): array
    {
        $info = getimagesizefromstring($bytes);
        if ($info === false) {
            throw new RuntimeException('Not a readable image.');
        }
        if ($info['mime'] === 'image/jpeg') {
            return [$bytes, 'jpeg', $info[0], $info[1]];
        }
        if ($info['mime'] !== 'image/png') {
            $image = imagecreatefromstring($bytes);
            if ($image === false) {
                throw new RuntimeException('Unsupported image type '.$info['mime']);
            }
            imagesavealpha($image, true);
            ob_start();
            imagepng($image);
            $bytes = (string) ob_get_clean();
        }

        return [$bytes, 'png', $info[0], $info[1]];
    }

    /**
     * @param  array<string, string>  $relations
     */
    public static function addMedia(ZipArchive $zip, array &$relations, string $bytes, string $extension): string
    {
        $number = count($relations) + 1;
        $rid = 'rIdSph'.$number;
        $relations[$rid] = 'media/sph-'.$number.'.'.$extension;
        $zip->addFromString('word/'.$relations[$rid], $bytes);

        return $rid;
    }

    /**
     * Pixels to EMU at 96 dpi, shrunk to the content width and the max image height.
     *
     * @return array{cx: int, cy: int}
     */
    public static function fit(float $width, float $height): array
    {
        $cx = max(1.0, $width) * self::PIXEL_EMU;
        $cy = max(1.0, $height) * self::PIXEL_EMU;
        $scale = min(1.0, self::CONTENT_WIDTH_EMU / $cx, self::MAX_IMAGE_HEIGHT_EMU / $cy);

        return ['cx' => (int) round($cx * $scale), 'cy' => (int) round($cy * $scale)];
    }

    /**
     * @return array{0: float, 1: float}
     */
    private static function svgSize(string $svg): array
    {
        return preg_match('/<svg[^>]*\swidth="([\d.]+)"[^>]*\sheight="([\d.]+)"/', $svg, $match) === 1
            ? [(float) $match[1], (float) $match[2]]
            : [800.0, 400.0];
    }

    /** PNG for Word versions without SVG support (before 2016). */
    private static function svgFallback(string $svg): string
    {
        if (class_exists(Imagick::class)) {
            try {
                $image = new Imagick;
                $image->setBackgroundColor(new ImagickPixel('white'));
                $image->readImageBlob($svg);
                $image->setImageFormat('png');

                return $image->getImageBlob();
            } catch (Throwable) {
                // No SVG delegate in this ImageMagick build
            }
        }

        // ponytail: blank fallback without Imagick; only Word < 2016 ever shows it
        return (string) base64_decode(self::BLANK_PNG);
    }

    /**
     * Put the issuer's letterhead image in the template header/footer at the template's width, height by
     * aspect ratio. A footer stays anchored to the page bottom. No image empties the part.
     */
    public static function letterhead(ZipArchive $zip, string $part, ?string $path, int $width): void
    {
        $xmlName = 'word/'.$part.'.xml';
        $relsName = 'word/_rels/'.$part.'.xml.rels';
        $root = str_starts_with($part, 'header') ? 'hdr' : 'ftr';

        if ($path === null) {
            $zip->addFromString($xmlName, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n"
                .'<w:'.$root.' xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:p/></w:'.$root.'>');
            $zip->addFromString($relsName, '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'."\n"
                .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"/>');

            return;
        }

        [$bytes, $extension, $pixelWidth, $pixelHeight] = self::image((string) file_get_contents($path));
        $height = (int) round($width * $pixelHeight / $pixelWidth);
        $media = 'media/letterhead-'.$part.'.'.$extension;
        $zip->addFromString('word/'.$media, $bytes);

        $xml = (string) $zip->getFromName($xmlName);
        $xml = (string) preg_replace('/<wp:extent cx="\d+" cy="\d+"\/>/', '<wp:extent cx="'.$width.'" cy="'.$height.'"/>', $xml, 1);
        $xml = (string) preg_replace('/<a:ext cx="\d+" cy="\d+"\/>/', '<a:ext cx="'.$width.'" cy="'.$height.'"/>', $xml, 1);
        $xml = (string) preg_replace('/(<wp:positionV relativeFrom="page"><wp:posOffset>)\d+/', '${1}'.(self::PAGE_HEIGHT_EMU - $height), $xml, 1);
        $zip->addFromString($xmlName, $xml);

        $rels = (string) $zip->getFromName($relsName);
        $zip->addFromString($relsName, (string) preg_replace('/Target="media\/[^"]+"/', 'Target="'.$media.'"', $rels, 1));
    }

    /**
     * @param  array<string, string>  $types  extension => content type
     */
    public static function ensureContentTypes(ZipArchive $zip, array $types): void
    {
        $xml = (string) $zip->getFromName('[Content_Types].xml');
        foreach ($types as $extension => $mime) {
            if (! str_contains($xml, 'Extension="'.$extension.'"')) {
                $xml = str_replace('<Default Extension="xml"', '<Default Extension="'.$extension.'" ContentType="'.$mime.'"/><Default Extension="xml"', $xml);
            }
        }
        $zip->addFromString('[Content_Types].xml', $xml);
    }
}
