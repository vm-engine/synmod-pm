<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

use Illuminate\Support\Str;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;

/**
 * Native, editable PowerPoint Gantt: task table + rectangles for bars + path shapes for links.
 */
final class PptxGanttRenderer
{
    public const ROW_HEIGHT = 228600;

    public const HEADER_HEIGHT = 457200;

    /** No, Task Name, Duration, Start, Finish (EMU) */
    private const TABLE_WIDTHS = [280000, 2170000, 500000, 780000, 780000];

    private const BAR = '8DB4E2';

    private const SUMMARY = '404040';

    private const SHADE = 'EDEDED';

    private const HEADER = 'DDEBF7';

    private const LINK = '2F6EBA';

    public function render(GanttLayout $layout, int $page, PptxXml $xml): string
    {
        $rows = $layout->pages[$page] ?? [];
        if ($rows === []) {
            return '';
        }

        $tableWidth = array_sum(self::TABLE_WIDTHS);
        $chartX = PptxXml::BODY_X + $tableWidth;
        $columnCount = max(1, count($layout->columns));
        $columnWidth = intdiv(PptxXml::BODY_CX - $tableWidth, $columnCount);
        $top = PptxXml::BODY_Y;
        $rowsTop = $top + self::HEADER_HEIGHT;
        $chartHeight = count($rows) * self::ROW_HEIGHT;

        $tableRows = [];
        $boldRows = [];
        foreach ($rows as $i => $row) {
            $task = $row->task;
            $tableRows[] = [
                (string) $task->number,
                ($task->parentId !== null ? '   ' : '').Str::limit($task->name, 45),
                $task->durationLabel(),
                $task->start->format('D d/m/y'),
                $task->finish->format('D d/m/y'),
            ];
            if ($task->isSummary) {
                $boldRows[] = $i;
            }
        }

        $shapes = $xml->table(PptxXml::BODY_X, $top, self::TABLE_WIDTHS, ['No', 'Task Name', 'Duration', 'Start', 'Finish'], $tableRows, [
            'font_size' => 800,
            'row_height' => self::ROW_HEIGHT,
            'header_height' => self::HEADER_HEIGHT,
            'compact' => true,
            'bold_rows' => $boldRows,
            'header_fill' => self::HEADER,
            'header_color' => '000000',
            'body_fill' => 'FFFFFF',
            'border' => 'BFBFBF',
        ]);

        $shapes .= $xml->rect($chartX, $top, $columnWidth * $columnCount, self::HEADER_HEIGHT, self::HEADER);

        // Column labels + shading, and group labels spanning their columns
        $groups = [];
        foreach ($layout->columns as $i => $column) {
            $x = $chartX + $i * $columnWidth;
            if ($column->shaded) {
                $shapes .= $xml->rect($x, $rowsTop, $columnWidth, $chartHeight, self::SHADE);
            }
            $shapes .= $xml->rect($x, $top + self::ROW_HEIGHT, $columnWidth, self::ROW_HEIGHT, null, 'rect', $column->label, 700, 'ctr');
            $last = array_key_last($groups);
            if ($last !== null && $groups[$last]['label'] === $column->groupLabel) {
                $groups[$last]['count']++;
            } else {
                $groups[] = ['label' => $column->groupLabel, 'start' => $i, 'count' => 1];
            }
        }
        foreach ($groups as $group) {
            $shapes .= $xml->rect($chartX + $group['start'] * $columnWidth, $top, $group['count'] * $columnWidth, self::ROW_HEIGHT, null, 'rect', $group['label'], 700, 'l');
        }

        // Bars
        $index = [];
        foreach ($rows as $i => $row) {
            $index[$row->task->id] = $i;
            $x = $chartX + (int) round($row->barStart * $columnWidth);
            $width = max(20000, (int) round($row->barSpan * $columnWidth));
            $y = $rowsTop + $i * self::ROW_HEIGHT;
            $shapes .= $row->task->isSummary
                ? $xml->rect($x, $y + 80000, $width, 60000, self::SUMMARY)
                : $xml->rect($x, $y + 45720, $width, self::ROW_HEIGHT - 91440, self::BAR);
        }

        // Finish-to-start links on this page
        foreach ($rows as $i => $row) {
            $predecessorId = $row->task->predecessorId;
            if ($predecessorId === null || ! isset($index[$predecessorId])) {
                continue;
            }
            $p = $rows[$index[$predecessorId]];
            $x1 = $chartX + (int) round(($p->barStart + $p->barSpan) * $columnWidth);
            $y1 = $rowsTop + $index[$predecessorId] * self::ROW_HEIGHT + intdiv(self::ROW_HEIGHT, 2);
            $xm = max($x1 + 40000, $chartX + (int) round($row->barStart * $columnWidth) + 40000);
            $y2 = $rowsTop + $i * self::ROW_HEIGHT + 45720;
            $shapes .= $xml->polyline([[$x1, $y1], [$xm, $y1], [$xm, $y2]], self::LINK);
        }

        return $shapes;
    }
}
