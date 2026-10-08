<?php

declare(strict_types=1);

require_once __DIR__.'/Fixtures.php';

use Barryvdh\DomPDF\Facade\Pdf;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\GanttSvgRenderer;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;

function thinkLayout(): GanttLayout
{
    $calendar = new WorkCalendar;

    return GanttLayout::build((new GanttScheduler)->schedule(thinkTasks(), '2025-07-01', $calendar), $calendar);
}

test('renders well-formed svg with every task and one arrow per on-page predecessor', function () {
    $svg = (new GanttSvgRenderer)->render(thinkLayout());
    $expectedLinks = count(array_filter(
        GanttScheduler::normalize(thinkTasks()),
        fn (array $task): bool => $task['predecessor_id'] !== null,
    ));

    expect(simplexml_load_string($svg))->not->toBeFalse()
        ->and($svg)->toContain('Kick of Meeting')
        ->and($svg)->toContain('Tue 01/07/25')
        ->and(substr_count($svg, 'class="bar"'))->toBe(11)
        ->and(substr_count($svg, 'class="link"'))->toBe($expectedLinks)
        ->and($expectedLinks)->toBe(8);
});

test('arrows can be switched off', function () {
    $svg = (new GanttSvgRenderer)->render(thinkLayout(), 0, arrows: false);

    expect(substr_count($svg, 'class="link"'))->toBe(0);
});

test('task names are xml-escaped', function () {
    $calendar = new WorkCalendar;
    $layout = GanttLayout::build((new GanttScheduler)->schedule(
        [['id' => 'a', 'name' => 'R&D <alpha>', 'parent_id' => null, 'duration' => 1, 'predecessor_id' => null]],
        '2026-01-19',
        $calendar,
    ), $calendar);

    expect((new GanttSvgRenderer)->render($layout))->toContain('R&amp;D &lt;alpha&gt;');
});

test('a missing page renders nothing', function () {
    expect((new GanttSvgRenderer)->render(thinkLayout(), 5))->toBe('');
});

test('dompdf renders the svg as an image', function () {
    $uri = GanttSvgRenderer::toDataUri((new GanttSvgRenderer)->render(thinkLayout()));
    $pdf = Pdf::loadHTML('<html><body><img src="'.$uri.'" style="width:100%"></body></html>')->output();

    expect(substr($pdf, 0, 4))->toBe('%PDF')
        ->and(strlen($pdf))->toBeGreaterThan(2000);
});
