<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Services\Proposal\Gantt\GanttSvgRenderer;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;

/**
 * 16:9 PDF version of the deck (spec §6.3). Static slides are images exported from the template.
 */
class PdfProposalRenderer
{
    /** Set to false if the Task 4 risk check showed dompdf mangles the SVG links. */
    public const ARROWS = true;

    /** 13.333in × 7.5in in points. */
    private const PAPER = [0, 0, 960, 540];

    /**
     * The deck's own fonts (Calibri, Arial Rounded MT Bold) are commercial, so they are not bundled.
     * Drop the Windows font files into storage/app/fonts and they are used; otherwise the bundled
     * open look-alikes (Carlito, Varela Round) are.
     *
     * @var array<string, array{0: string, 1: string}> role => [licensed file, bundled fallback]
     */
    private const FONT_FILES = [
        'regular' => ['calibri.ttf', 'Carlito-Regular.ttf'],
        'bold' => ['calibrib.ttf', 'Carlito-Bold.ttf'],
        'italic' => ['calibrii.ttf', 'Carlito-Italic.ttf'],
        'bolditalic' => ['calibriz.ttf', 'Carlito-BoldItalic.ttf'],
        'rounded' => ['arlrdbd.ttf', 'VarelaRound-Regular.ttf'],
    ];

    public function __construct(private readonly GanttSvgRenderer $gantt) {}

    public function render(ProposalDocumentData $data): string
    {
        // dompdf caches registered @font-face fonts here. It must be writable by the web server too,
        // otherwise a font first used in a web request silently falls back to the default font.
        File::ensureDirectoryExists(storage_path('fonts'), 0775);

        $ganttImages = [];
        foreach (array_keys($data->gantt->pages) as $page) {
            $ganttImages[] = GanttSvgRenderer::toDataUri($this->gantt->render($data->gantt, $page, self::ARROWS));
        }

        $pdf = Pdf::loadView('pm::pdf.proposal.document', [
            'data' => $data,
            'variant' => $data->type === ProposalType::Website ? 'website' : 'application',
            'ganttImages' => $ganttImages,
            'asset' => fn (string $file): string => TemplateMap::asset($file),
            'fonts' => self::fonts(),
        ]);
        $pdf->setPaper(self::PAPER);
        $pdf->setOption('chroot', [base_path(), TemplateMap::packageRoot(), storage_path()]);

        return $pdf->output();
    }

    /**
     * Absolute font paths per role, preferring licensed files in $licensedDir (matched case-insensitively).
     *
     * @return array{body: array{regular: string, bold: string, italic: string, bolditalic: string}, rounded: string}
     */
    public static function fonts(?string $licensedDir = null): array
    {
        $licensedDir ??= storage_path('app/fonts');
        $available = [];
        foreach (is_dir($licensedDir) ? (scandir($licensedDir) ?: []) : [] as $file) {
            $available[strtolower($file)] = $licensedDir.'/'.$file;
        }

        $resolve = fn (string $role): string => $available[self::FONT_FILES[$role][0]]
            ?? TemplateMap::packageRoot().'/resources/fonts/'.self::FONT_FILES[$role][1];

        return [
            'body' => [
                'regular' => $resolve('regular'),
                'bold' => $resolve('bold'),
                'italic' => $resolve('italic'),
                'bolditalic' => $resolve('bolditalic'),
            ],
            'rounded' => $resolve('rounded'),
        ];
    }
}
