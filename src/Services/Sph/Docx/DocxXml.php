<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Sph\Docx;

use VmEngine\Pm\Services\Sph\MarkdownBlocks;

/**
 * WordprocessingML snippets for the SPH document view. Element order inside *Pr follows the schema.
 *
 * @phpstan-import-type Block from MarkdownBlocks
 */
final class DocxXml
{
    /** Hanging indent per list level, in twips (0.25in). */
    public const INDENT = 360;

    public static function e(string $text): string
    {
        // Drop characters XML 1.0 forbids (e.g. vertical tab or form feed pasted from Word), which would corrupt the DOCX
        $text = (string) preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $text);

        return htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param  array{bold?: bool, italic?: bool, underline?: bool, size?: int}  $style  size in half-points
     */
    public static function run(string $text, array $style = []): string
    {
        $props = (! empty($style['bold']) ? '<w:b/>' : '')
            .(! empty($style['italic']) ? '<w:i/>' : '')
            .(! empty($style['underline']) ? '<w:u w:val="single"/>' : '')
            .(isset($style['size']) ? '<w:sz w:val="'.$style['size'].'"/><w:szCs w:val="'.$style['size'].'"/>' : '');

        return '<w:r>'.($props !== '' ? '<w:rPr>'.$props.'</w:rPr>' : '').'<w:t xml:space="preserve">'.self::e($text).'</w:t></w:r>';
    }

    /**
     * @param  array{align?: string, before?: int, after?: int, indent?: int, hanging?: int, keepNext?: bool, pageBreakBefore?: bool}  $style
     */
    public static function paragraph(string $runs, array $style = []): string
    {
        $props = (! empty($style['keepNext']) ? '<w:keepNext/>' : '')
            .(! empty($style['pageBreakBefore']) ? '<w:pageBreakBefore/>' : '')
            .'<w:spacing w:before="'.($style['before'] ?? 0).'" w:after="'.($style['after'] ?? 0).'"/>'
            .(isset($style['indent']) || isset($style['hanging']) ? '<w:ind w:left="'.($style['indent'] ?? 0).'" w:hanging="'.($style['hanging'] ?? 0).'"/>' : '')
            .(isset($style['align']) ? '<w:jc w:val="'.$style['align'].'"/>' : '');

        return '<w:p><w:pPr>'.$props.'</w:pPr>'.$runs.'</w:p>';
    }

    /**
     * @param  array{bold?: bool, italic?: bool, underline?: bool, size?: int}  $runStyle
     * @param  array{align?: string, before?: int, after?: int, indent?: int, hanging?: int, keepNext?: bool, pageBreakBefore?: bool}  $paragraphStyle
     */
    public static function text(string $text, array $runStyle = [], array $paragraphStyle = []): string
    {
        return self::paragraph($text === '' ? '' : self::run($text, $runStyle), $paragraphStyle);
    }

    /**
     * Markdown blocks as paragraphs; a marker hangs in the indent of its level.
     *
     * @param  list<Block>  $blocks
     */
    public static function blocks(array $blocks): string
    {
        $xml = '';
        foreach ($blocks as $block) {
            $runs = $block['marker'] !== '' ? self::run($block['marker']).'<w:r><w:tab/></w:r>' : '';
            foreach ($block['runs'] as $run) {
                $runs .= self::run($run['text'], ['bold' => $run['bold'], 'italic' => $run['italic']]);
            }
            $xml .= self::paragraph($runs, [
                'after' => 40,
                'indent' => $block['level'] * self::INDENT,
                'hanging' => $block['marker'] !== '' ? self::INDENT : 0,
            ]);
        }

        return $xml;
    }

    /**
     * @param  list<string>  $lines
     */
    public static function bullets(array $lines): string
    {
        return self::blocks(array_map(
            fn (string $line): array => ['level' => 1, 'marker' => '•', 'runs' => [['text' => $line, 'bold' => false, 'italic' => false]]],
            $lines,
        ));
    }

    /** Inline picture in its own run. With $svgRid the SVG is shown and $rid is the PNG fallback. */
    public static function image(string $rid, int $cx, int $cy, int $id, ?string $svgRid = null): string
    {
        $svg = $svgRid === null ? '' : '<a:extLst><a:ext uri="{96DAC541-7B7A-43D3-8B79-37D633B846F1}">'
            .'<asvg:svgBlip xmlns:asvg="http://schemas.microsoft.com/office/drawing/2016/SVG/main" r:embed="'.$svgRid.'"/></a:ext></a:extLst>';

        return '<w:r><w:drawing><wp:inline distT="0" distB="0" distL="0" distR="0">'
            .'<wp:extent cx="'.$cx.'" cy="'.$cy.'"/><wp:docPr id="'.$id.'" name="Picture '.$id.'"/>'
            .'<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            .'<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">'
            .'<pic:nvPicPr><pic:cNvPr id="'.$id.'" name="Picture '.$id.'"/><pic:cNvPicPr/></pic:nvPicPr>'
            .'<pic:blipFill><a:blip r:embed="'.$rid.'">'.$svg.'</a:blip><a:stretch><a:fillRect/></a:stretch></pic:blipFill>'
            .'<pic:spPr><a:xfrm><a:off x="0" y="0"/><a:ext cx="'.$cx.'" cy="'.$cy.'"/></a:xfrm><a:prstGeom prst="rect"><a:avLst/></a:prstGeom></pic:spPr>'
            .'</pic:pic></a:graphicData></a:graphic></wp:inline></w:drawing></w:r>';
    }

    /**
     * @param  array{span?: int, fill?: string, valign?: string}  $style
     */
    public static function cell(int $width, string $content, array $style = []): string
    {
        return '<w:tc><w:tcPr><w:tcW w:w="'.$width.'" w:type="dxa"/>'
            .(isset($style['span']) ? '<w:gridSpan w:val="'.$style['span'].'"/>' : '')
            .(isset($style['fill']) ? '<w:shd w:val="clear" w:color="auto" w:fill="'.$style['fill'].'"/>' : '')
            .'<w:vAlign w:val="'.($style['valign'] ?? 'top').'"/></w:tcPr>'
            .($content !== '' ? $content : '<w:p/>').'</w:tc>';
    }

    public static function row(string $cells, bool $header = false): string
    {
        return '<w:tr>'.($header ? '<w:trPr><w:tblHeader/></w:trPr>' : '').$cells.'</w:tr>';
    }

    /**
     * @param  list<int>  $widths  twips
     */
    public static function table(array $widths, string $rows, bool $borders = true): string
    {
        $border = $borders
            ? '<w:tblBorders>'.implode('', array_map(
                fn (string $side): string => '<w:'.$side.' w:val="single" w:sz="4" w:space="0" w:color="000000"/>',
                ['top', 'left', 'bottom', 'right', 'insideH', 'insideV'],
            )).'</w:tblBorders>'
            : '';

        return '<w:tbl><w:tblPr><w:tblW w:w="'.array_sum($widths).'" w:type="dxa"/>'.$border
            .'<w:tblLayout w:type="fixed"/><w:tblCellMar><w:left w:w="80" w:type="dxa"/><w:right w:w="80" w:type="dxa"/></w:tblCellMar></w:tblPr>'
            .'<w:tblGrid>'.implode('', array_map(fn (int $w): string => '<w:gridCol w:w="'.$w.'"/>', $widths)).'</w:tblGrid>'
            .$rows.'</w:tbl>';
    }
}
