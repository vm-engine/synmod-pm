<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

/**
 * DrawingML snippets for generated slide content. One instance per slide (shape ids are per slide).
 */
final class PptxXml
{
    /** Content area under the title bar (EMU). */
    public const BODY_X = 364434;

    public const BODY_Y = 1700000;

    public const BODY_CX = 11463131;

    public const BODY_CY = 4850000;

    /** Text box of the deck's text slides (see ProposalDocumentData::TEXT_*), in EMU (12700 per pt). */
    public const TEXT_X = 838200;

    public const TEXT_Y = 1825625;

    public const TEXT_CX = 10515600;

    public const TEXT_CY = 4521200;

    public const ORANGE = 'ED7D31';

    private int $id = 1000;

    public static function esc(string $text): string
    {
        return htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param  list<array<string, mixed>>  $paragraphs  {text, lead?, level?, bullet?, bold?, size?}
     */
    public function textBox(int $x, int $y, int $cx, int $cy, array $paragraphs): string
    {
        $id = $this->nextId();
        $body = '';
        foreach ($paragraphs as $paragraph) {
            $level = (int) ($paragraph['level'] ?? 0);
            $bullet = (bool) ($paragraph['bullet'] ?? false);
            $size = (int) ($paragraph['size'] ?? 1600);
            $marL = $level * 457200 + ($bullet ? 285750 : 0);
            $body .= '<a:p><a:pPr marL="'.$marL.'" indent="'.($bullet ? -285750 : 0).'"><a:spcBef><a:spcPts val="600"/></a:spcBef>'
                .($bullet ? '<a:buFont typeface="Arial"/><a:buChar char="&#8226;"/>' : '<a:buNone/>').'</a:pPr>';
            if ((string) ($paragraph['lead'] ?? '') !== '') {
                $body .= $this->run((string) $paragraph['lead'], $size, true);
            }
            $body .= $this->run((string) $paragraph['text'], $size, (bool) ($paragraph['bold'] ?? false)).'</a:p>';
        }
        if ($body === '') {
            $body = '<a:p><a:endParaRPr lang="id-ID"/></a:p>';
        }

        return '<p:sp><p:nvSpPr><p:cNvPr id="'.$id.'" name="Proposal Text '.$id.'"/><p:cNvSpPr txBox="1"/><p:nvPr/></p:nvSpPr>'
            .'<p:spPr>'.$this->xfrm($x, $y, $cx, $cy).'<a:prstGeom prst="rect"><a:avLst/></a:prstGeom><a:noFill/></p:spPr>'
            .'<p:txBody><a:bodyPr wrap="square" lIns="91440" tIns="45720" rIns="91440" bIns="45720"><a:normAutofit/></a:bodyPr><a:lstStyle/>'.$body.'</p:txBody></p:sp>';
    }

    /**
     * @param  list<int>  $widths
     * @param  list<string>  $header
     * @param  list<list<string|list<string>|array<string, mixed>>>  $rows
     * @param  array<string, mixed>  $opts
     */
    public function table(int $x, int $y, array $widths, array $header, array $rows, array $opts = []): string
    {
        $o = $opts + [
            'header_fill' => self::ORANGE,
            'header_color' => 'FFFFFF',
            'body_fill' => 'FBE9E1',
            'border' => self::ORANGE,
            'font_size' => 1200,
            'row_height' => 370840,
            'header_height' => null,
            'compact' => false,
            'bold_rows' => [],
            'align' => [],
        ];
        $headerHeight = (int) ($o['header_height'] ?? $o['row_height']);
        $id = $this->nextId();

        $xml = '<a:tr h="'.$headerHeight.'">';
        foreach ($header as $label) {
            $xml .= $this->cell(['lines' => [$label], 'bullets' => false, 'bold' => true, 'align' => 'ctr', 'span' => 1], $o['header_fill'], $o['header_color'], $o);
        }
        $xml .= '</a:tr>';

        foreach ($rows as $r => $row) {
            $xml .= '<a:tr h="'.$o['row_height'].'">';
            $column = 0;
            foreach ($row as $cell) {
                $spec = $this->cellSpec($cell, (string) ($o['align'][$column] ?? 'l'), in_array($r, $o['bold_rows'], true));
                $xml .= $this->cell($spec, $o['body_fill'], '000000', $o);
                for ($m = 1; $m < $spec['span']; $m++) {
                    $xml .= '<a:tc hMerge="1"><a:txBody><a:bodyPr/><a:lstStyle/><a:p><a:endParaRPr lang="id-ID" sz="'.$o['font_size'].'"/></a:p></a:txBody><a:tcPr/></a:tc>';
                }
                $column += $spec['span'];
            }
            $xml .= '</a:tr>';
        }

        $grid = implode('', array_map(fn (int $w): string => '<a:gridCol w="'.$w.'"/>', $widths));
        $height = $headerHeight + count($rows) * (int) $o['row_height'];

        return '<p:graphicFrame><p:nvGraphicFramePr><p:cNvPr id="'.$id.'" name="Proposal Table '.$id.'"/><p:cNvGraphicFramePr><a:graphicFrameLocks noGrp="1"/></p:cNvGraphicFramePr><p:nvPr/></p:nvGraphicFramePr>'
            .'<p:xfrm><a:off x="'.$x.'" y="'.$y.'"/><a:ext cx="'.array_sum($widths).'" cy="'.$height.'"/></p:xfrm>'
            .'<a:graphic><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/table"><a:tbl><a:tblPr firstRow="1"/><a:tblGrid>'.$grid.'</a:tblGrid>'.$xml.'</a:tbl></a:graphicData></a:graphic></p:graphicFrame>';
    }

    public function rect(int $x, int $y, int $cx, int $cy, ?string $fill, string $prst = 'rect', ?string $text = null, int $size = 1200, string $align = 'l', string $color = '000000', bool $bold = false): string
    {
        $id = $this->nextId();
        $fillXml = $fill === null ? '<a:noFill/>' : '<a:solidFill><a:srgbClr val="'.$fill.'"/></a:solidFill>';
        $txBody = $text === null ? '' : '<p:txBody><a:bodyPr wrap="square" lIns="18288" tIns="0" rIns="18288" bIns="0" anchor="ctr"/><a:lstStyle/><a:p><a:pPr algn="'.$align.'"/>'
            .$this->run($text, $size, $bold, $color).'</a:p></p:txBody>';

        return '<p:sp><p:nvSpPr><p:cNvPr id="'.$id.'" name="Proposal Shape '.$id.'"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>'
            .'<p:spPr>'.$this->xfrm($x, $y, $cx, $cy).'<a:prstGeom prst="'.$prst.'"><a:avLst/></a:prstGeom>'.$fillXml.'<a:ln><a:noFill/></a:ln></p:spPr>'.$txBody.'</p:sp>';
    }

    public function picture(string $rId, int $x, int $y, int $cx, int $cy): string
    {
        $id = $this->nextId();

        return '<p:pic><p:nvPicPr><p:cNvPr id="'.$id.'" name="Proposal Picture '.$id.'"/><p:cNvPicPr><a:picLocks noChangeAspect="1"/></p:cNvPicPr><p:nvPr/></p:nvPicPr>'
            .'<p:blipFill><a:blip r:embed="'.self::esc($rId).'"/><a:stretch><a:fillRect/></a:stretch></p:blipFill>'
            .'<p:spPr>'.$this->xfrm($x, $y, $cx, $cy).'<a:prstGeom prst="rect"><a:avLst/></a:prstGeom></p:spPr></p:pic>';
    }

    /**
     * Open path with an arrow head at the last point (Gantt links).
     *
     * @param  non-empty-list<array{0: int, 1: int}>  $points
     */
    public function polyline(array $points, string $color): string
    {
        $xs = array_column($points, 0);
        $ys = array_column($points, 1);
        $minX = min($xs);
        $minY = min($ys);
        $w = max(1, max($xs) - $minX);
        $h = max(1, max($ys) - $minY);
        $path = '';
        foreach ($points as $i => [$px, $py]) {
            $pt = '<a:pt x="'.($px - $minX).'" y="'.($py - $minY).'"/>';
            $path .= $i === 0 ? '<a:moveTo>'.$pt.'</a:moveTo>' : '<a:lnTo>'.$pt.'</a:lnTo>';
        }
        $id = $this->nextId();

        return '<p:sp><p:nvSpPr><p:cNvPr id="'.$id.'" name="Proposal Link '.$id.'"/><p:cNvSpPr/><p:nvPr/></p:nvSpPr>'
            .'<p:spPr>'.$this->xfrm($minX, $minY, $w, $h)
            .'<a:custGeom><a:avLst/><a:gdLst/><a:ahLst/><a:cxnLst/><a:rect l="0" t="0" r="r" b="b"/><a:pathLst><a:path w="'.$w.'" h="'.$h.'" fill="none">'.$path.'</a:path></a:pathLst></a:custGeom>'
            .'<a:noFill/><a:ln w="9525"><a:solidFill><a:srgbClr val="'.$color.'"/></a:solidFill><a:tailEnd type="triangle" w="sm" len="sm"/></a:ln></p:spPr></p:sp>';
    }

    private function nextId(): int
    {
        return ++$this->id;
    }

    private function xfrm(int $x, int $y, int $cx, int $cy): string
    {
        return '<a:xfrm><a:off x="'.$x.'" y="'.$y.'"/><a:ext cx="'.$cx.'" cy="'.$cy.'"/></a:xfrm>';
    }

    private function run(string $text, int $size, bool $bold, string $color = '000000'): string
    {
        return '<a:r><a:rPr lang="id-ID" sz="'.$size.'"'.($bold ? ' b="1"' : '').' dirty="0"><a:solidFill><a:srgbClr val="'.$color.'"/></a:solidFill></a:rPr><a:t>'.self::esc($text).'</a:t></a:r>';
    }

    /**
     * @param  string|list<string>|array<string, mixed>  $cell
     * @return array{lines: list<string>, bullets: bool, bold: bool, align: string, span: int}
     */
    private function cellSpec(string|array $cell, string $align, bool $bold): array
    {
        if (is_string($cell)) {
            return ['lines' => [$cell], 'bullets' => false, 'bold' => $bold, 'align' => $align, 'span' => 1];
        }
        if (array_is_list($cell)) {
            $lines = array_map('strval', $cell);

            return ['lines' => $lines === [] ? [''] : $lines, 'bullets' => count($lines) > 1, 'bold' => $bold, 'align' => $align, 'span' => 1];
        }

        return [
            'lines' => explode("\n", (string) ($cell['text'] ?? '')),
            'bullets' => false,
            'bold' => (bool) ($cell['bold'] ?? $bold),
            'align' => (string) ($cell['align'] ?? $align),
            'span' => max(1, (int) ($cell['span'] ?? 1)),
        ];
    }

    /**
     * @param  array{lines: list<string>, bullets: bool, bold: bool, align: string, span: int}  $spec
     * @param  array<string, mixed>  $o
     */
    private function cell(array $spec, string $fill, string $color, array $o): string
    {
        $paragraphs = '';
        foreach ($spec['lines'] as $line) {
            $pPr = $spec['bullets']
                ? '<a:pPr algn="'.$spec['align'].'" marL="171450" indent="-171450"><a:buFont typeface="Arial"/><a:buChar char="&#8226;"/></a:pPr>'
                : '<a:pPr algn="'.$spec['align'].'"><a:buNone/></a:pPr>';
            $paragraphs .= '<a:p>'.$pPr.$this->run($line, (int) $o['font_size'], $spec['bold'], $color).'</a:p>';
        }
        $margins = $o['compact']
            ? ' marL="45720" marR="45720" marT="0" marB="0"'
            : ' marL="91440" marR="91440" marT="45720" marB="45720"';
        $border = fn (string $side): string => '<a:'.$side.' w="9525"><a:solidFill><a:srgbClr val="'.$o['border'].'"/></a:solidFill></a:'.$side.'>';

        return '<a:tc'.($spec['span'] > 1 ? ' gridSpan="'.$spec['span'].'"' : '').'><a:txBody><a:bodyPr/><a:lstStyle/>'.$paragraphs.'</a:txBody>'
            .'<a:tcPr'.$margins.' anchor="ctr">'.$border('lnL').$border('lnR').$border('lnT').$border('lnB')
            .'<a:solidFill><a:srgbClr val="'.$fill.'"/></a:solidFill></a:tcPr></a:tc>';
    }
}
