<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Gantt;

/**
 * MS-Project-style Gantt as SVG: task table on the left, timeline on the right.
 * Used for the browser preview and (as a data-URI image) in the PDF.
 */
final class GanttSvgRenderer
{
    private const ROW_HEIGHT = 22;

    private const HEADER_HEIGHT = 40;

    /** @var array<string, int> label => width */
    private const TABLE_COLUMNS = ['No' => 28, 'Task Name' => 230, 'Duration' => 60, 'Start' => 74, 'Finish' => 74];

    private const BAR_COLOR = '#8DB4E2';

    private const SUMMARY_COLOR = '#404040';

    private const SHADE_COLOR = '#EDEDED';

    private const GRID_COLOR = '#D0D0D0';

    private const HEADER_FILL = '#DDEBF7';

    private const LINK_COLOR = '#2F6EBA';

    public function render(GanttLayout $layout, int $page = 0, bool $arrows = true): string
    {
        $rows = $layout->pages[$page] ?? [];
        if ($rows === []) {
            return '';
        }

        $tableWidth = array_sum(self::TABLE_COLUMNS);
        $colWidth = $layout->scale === 'day' ? 16 : 36;
        $chartWidth = count($layout->columns) * $colWidth;
        $width = $tableWidth + $chartWidth;
        $height = self::HEADER_HEIGHT + count($rows) * self::ROW_HEIGHT;
        $half = self::HEADER_HEIGHT / 2;

        $svg = [];
        $svg[] = sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" font-family="Helvetica, Arial, sans-serif" font-size="10">', $width, $height, $width, $height);
        $svg[] = $this->rect(0, 0, $width, self::HEADER_HEIGHT, self::HEADER_FILL);

        // Table header
        $x = 0;
        foreach (self::TABLE_COLUMNS as $label => $columnWidth) {
            $svg[] = $this->text($x + 4, self::HEADER_HEIGHT - 8, $label, bold: true);
            $svg[] = $this->line($x, 0, $x, $height);
            $x += $columnWidth;
        }

        // Timeline header + shading
        $previousGroup = null;
        foreach ($layout->columns as $i => $column) {
            $cx = $tableWidth + $i * $colWidth;
            if ($column->shaded) {
                $svg[] = $this->rect($cx, self::HEADER_HEIGHT, $colWidth, $height - self::HEADER_HEIGHT, self::SHADE_COLOR);
            }
            $svg[] = $this->text($cx + $colWidth / 2, self::HEADER_HEIGHT - 8, $column->label, anchor: 'middle');
            if ($column->groupLabel !== $previousGroup) {
                $svg[] = $this->text($cx + 3, $half - 6, $column->groupLabel);
                $svg[] = $this->line($cx, 0, $cx, $half);
                $previousGroup = $column->groupLabel;
            }
        }
        $svg[] = $this->line($tableWidth, $half, $width, $half);
        $svg[] = $this->line($tableWidth, 0, $tableWidth, $height);

        // Rows
        $index = [];
        foreach ($rows as $r => $row) {
            $index[$row->task->id] = $r;
            $task = $row->task;
            $y = self::HEADER_HEIGHT + $r * self::ROW_HEIGHT;
            $baseline = $y + self::ROW_HEIGHT - 7;
            $cells = [
                (string) $task->number,
                $task->name,
                $task->durationLabel(),
                $task->start->format('D d/m/y'),
                $task->finish->format('D d/m/y'),
            ];
            $x = 0;
            $c = 0;
            foreach (self::TABLE_COLUMNS as $columnWidth) {
                $indent = $c === 1 ? $task->level() * 12 : 0;
                $svg[] = $this->text($x + 4 + $indent, $baseline, $cells[$c], bold: $task->isSummary);
                $x += $columnWidth;
                $c++;
            }
            $svg[] = $this->line(0, $y + self::ROW_HEIGHT, $width, $y + self::ROW_HEIGHT);

            $barX = $tableWidth + $row->barStart * $colWidth;
            $barWidth = max(2.0, $row->barSpan * $colWidth);
            $svg[] = $task->isSummary
                ? $this->rect($barX, $y + 8, $barWidth, 5, self::SUMMARY_COLOR, 'bar')
                : $this->rect($barX, $y + 5, $barWidth, self::ROW_HEIGHT - 10, self::BAR_COLOR, 'bar');
        }

        // Finish-to-start links: right from the predecessor's end, then down into the successor's start
        if ($arrows) {
            foreach ($rows as $r => $row) {
                $predecessorId = $row->task->predecessorId;
                if ($predecessorId === null || ! isset($index[$predecessorId])) {
                    continue;
                }
                $p = $rows[$index[$predecessorId]];
                $x1 = $tableWidth + ($p->barStart + $p->barSpan) * $colWidth;
                $y1 = self::HEADER_HEIGHT + $index[$predecessorId] * self::ROW_HEIGHT + self::ROW_HEIGHT / 2;
                $xm = max($x1 + 4, $tableWidth + $row->barStart * $colWidth + 4);
                $y2 = self::HEADER_HEIGHT + $r * self::ROW_HEIGHT + 5;
                $svg[] = sprintf(
                    '<g class="link"><polyline points="%.1f,%.1f %.1f,%.1f %.1f,%.1f" fill="none" stroke="%s" stroke-width="1"/><polygon points="%.1f,%.1f %.1f,%.1f %.1f,%.1f" fill="%s"/></g>',
                    $x1, $y1, $xm, $y1, $xm, $y2 - 4, self::LINK_COLOR,
                    $xm - 3, $y2 - 4, $xm + 3, $y2 - 4, $xm, $y2, self::LINK_COLOR,
                );
            }
        }

        $svg[] = '</svg>';

        return implode('', $svg);
    }

    public static function toDataUri(string $svg): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    private function rect(float $x, float $y, float $w, float $h, string $fill, ?string $class = null): string
    {
        return sprintf('<rect%s x="%.1f" y="%.1f" width="%.1f" height="%.1f" fill="%s"/>', $class ? ' class="'.$class.'"' : '', $x, $y, $w, $h, $fill);
    }

    private function line(float $x1, float $y1, float $x2, float $y2): string
    {
        return sprintf('<line x1="%.1f" y1="%.1f" x2="%.1f" y2="%.1f" stroke="%s" stroke-width="0.5"/>', $x1, $y1, $x2, $y2, self::GRID_COLOR);
    }

    private function text(float $x, float $y, string $text, bool $bold = false, string $anchor = 'start'): string
    {
        return sprintf(
            '<text x="%.1f" y="%.1f" text-anchor="%s"%s>%s</text>',
            $x, $y, $anchor, $bold ? ' font-weight="bold"' : '',
            htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8'),
        );
    }
}
