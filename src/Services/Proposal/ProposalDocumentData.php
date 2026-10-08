<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Helpers\Terbilang;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Gantt\GanttLayout;
use VmEngine\Pm\Services\Proposal\Gantt\GanttScheduler;
use VmEngine\Pm\Services\Proposal\Gantt\WorkCalendar;

/**
 * Everything the PPTX and PDF renderers need, computed once from a proposal.
 */
final readonly class ProposalDocumentData
{
    /** Phrase in the template's Background slide that changes with the proposal type. */
    public const WEBSITE_PHRASE = 'memiliki sebuah website yang informatif, dinamis, professional dan elegant';

    public const APPLICATION_PHRASE = 'memiliki sebuah aplikasi yang handal, aman, efektif dan efisien';

    /**
     * Text box of the deck's text slides (Background / Feature & Module), in points.
     * x matches the slide title so items line up with it; the PPTX and PDF renderers both use it.
     */
    public const TEXT_X = 66.0;

    public const TEXT_Y = 143.75;

    public const TEXT_WIDTH = 828.0;

    public const TEXT_HEIGHT = 356.0;

    /** Inner margin of a PowerPoint text box: 7.2pt left/right, 3.6pt top/bottom. */
    public const TEXT_INSET_X = 7.2;

    public const TEXT_INSET_Y = 3.6;

    /** Hanging indent per outline level and for a bullet, in points (matches PptxXml::textBox). */
    public const LEVEL_INDENT = 36.0;

    public const BULLET_INDENT = 22.5;

    /** Space before each paragraph and line height factor, as rendered in both formats. */
    public const PARAGRAPH_SPACING = 6.0;

    public const LINE_HEIGHT = 1.2;

    /** Average Calibri glyph width as a fraction of the font size (slightly generous). */
    private const AVERAGE_CHAR_WIDTH = 0.5;

    /** Fill at most this share of the box: the width estimate is approximate. */
    private const FILL_RATIO = 0.95;

    /**
     * @param  list<list<array<string, mixed>>>  $goalPages
     * @param  list<list<array<string, mixed>>>  $menuPages
     * @param  list<array{path: string, caption: string}>  $mockups
     * @param  list<list<array<string, mixed>>>  $featurePages
     * @param  list<array{section: string, features: list<string>, price: string}>  $costing
     * @param  list<array{name: string, specs: list<string>, price: string}>  $hosting
     * @param  list<array{item: string, description: string}>  $deliverables
     * @param  list<string>  $afterSales
     */
    public function __construct(
        public ProposalType $type,
        public string $number,
        public string $title,
        public string $subject,
        public string $solutionLabel,
        public string $developmentLabel,
        public string $backgroundPhrase,
        public array $goalPages,
        public array $menuPages,
        public array $mockups,
        public array $featurePages,
        public array $costing,
        public string $grandTotalLabel,
        public string $grandTotalWords,
        public bool $taxNote,
        public array $hosting,
        public bool $hostingOptional,
        public GanttLayout $gantt,
        public string $workdayNote,
        public array $deliverables,
        public array $afterSales,
    ) {}

    public static function build(ProjectProposal $proposal): self
    {
        $website = $proposal->type === ProposalType::Website;
        $months = (string) $proposal->maintenance_months;
        $fillMonths = fn (string $text): string => str_replace('{months}', $months, $text);
        $scope = $proposal->scope ?? [];
        $timeline = $proposal->timeline ?? [];

        $calendar = WorkCalendar::fromSets(
            Holiday::dates(),
            $timeline['extra_holidays'] ?? [],
            $timeline['worked_holidays'] ?? [],
        );
        $tasks = (new GanttScheduler)->schedule(
            $timeline['tasks'] ?? [],
            $timeline['start_date'] ?? CarbonImmutable::now()->toDateString(),
            $calendar,
        );
        $gantt = GanttLayout::build($tasks, $calendar);
        $holidaysInSpan = $gantt->start !== null && $gantt->finish !== null
            && $calendar->holidaysBetween($gantt->start, $gantt->finish) > 0;

        return new self(
            type: $proposal->type,
            number: $proposal->number ?? self::nextNumber($proposal),
            title: $proposal->title,
            subject: $proposal->subject,
            solutionLabel: $website ? 'Web Development' : 'Application Development',
            developmentLabel: $website ? 'Website Development' : 'Application Development',
            backgroundPhrase: $website ? self::WEBSITE_PHRASE : self::APPLICATION_PHRASE,
            goalPages: $website ? self::paginate(self::goalParagraphs((string) ($scope['goal'] ?? ''))) : [],
            menuPages: $website ? self::paginate(self::menuParagraphs($scope['menu'] ?? [])) : [],
            mockups: $website ? self::mockups($scope['mockups'] ?? []) : [],
            featurePages: self::paginate(
                $website ? self::websiteFeatureParagraphs($proposal->features ?? []) : self::moduleParagraphs($proposal->features ?? []),
            ),
            costing: array_map(fn (array $row): array => [
                'section' => (string) ($row['section'] ?? ''),
                'features' => self::lines($row['features'] ?? []),
                'price' => self::money((float) ($row['price'] ?? 0)),
            ], $proposal->costing ?? []),
            grandTotalLabel: self::money((float) $proposal->grand_total),
            grandTotalWords: Str::upper(Terbilang::rupiah((float) $proposal->grand_total)),
            taxNote: $proposal->tax_note,
            hosting: array_map(fn (array $row): array => [
                'name' => (string) ($row['name'] ?? ''),
                'specs' => self::lines($row['specs'] ?? []),
                'price' => self::money((float) ($row['price'] ?? 0), suffix: false).'/'.((string) ($row['period'] ?? 'tahun'))
                    .(! empty($row['start_from']) ? ' *start from' : ''),
            ], $proposal->hosting ?? []),
            hostingOptional: collect($proposal->hosting ?? [])->contains(fn (array $row): bool => ! empty($row['optional'])),
            gantt: $gantt,
            workdayNote: 'Hari kerja: Senin - Jumat'.($holidaysInSpan ? ', di luar hari libur' : ''),
            deliverables: array_map(fn (array $row): array => [
                'item' => $fillMonths((string) ($row['item'] ?? '')),
                'description' => $fillMonths((string) ($row['description'] ?? '')),
            ], $proposal->deliverables ?? []),
            afterSales: array_map(fn (array $row): string => $fillMonths((string) ($row['label'] ?? '')), $proposal->after_sales ?? []),
        );
    }

    /**
     * The number the first publish will assign. Generating does not consume the index
     * (only incrementIndex() does), so previews never skip numbers.
     */
    private static function nextNumber(ProjectProposal $proposal): string
    {
        return Documents::PROPOSAL->handler()->generateDocumentNumber($proposal->project, Documents::PROPOSAL);
    }

    public static function money(float $amount, bool $suffix = true): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.').($suffix ? ',-' : '');
    }

    /**
     * Split paragraphs into pages that fit the text box. A paragraph marked 'keep' (a heading)
     * never ends a page: it moves to the next one with the content that follows it.
     *
     * @param  list<array<string, mixed>>  $paragraphs
     * @return list<list<array<string, mixed>>>
     */
    public static function paginate(array $paragraphs): array
    {
        $budget = (self::TEXT_HEIGHT - 2 * self::TEXT_INSET_Y) * self::FILL_RATIO;
        $pages = [];
        $current = [];
        $used = 0.0;
        foreach ($paragraphs as $paragraph) {
            $height = self::paragraphHeight($paragraph);
            if ($current !== [] && $used + $height > $budget) {
                $carry = [];
                while ($current !== [] && ! empty($current[array_key_last($current)]['keep'])) {
                    array_unshift($carry, array_pop($current));
                }
                if ($current === []) {
                    // A page of nothing but headings: keep them rather than loop forever
                    $current = $carry;
                    $carry = [];
                }
                $pages[] = $current;
                $current = $carry;
                $used = array_sum(array_map(self::paragraphHeight(...), $carry));
            }
            $current[] = $paragraph;
            $used += $height;
        }
        if ($current !== []) {
            $pages[] = $current;
        }

        return $pages;
    }

    /**
     * Estimated rendered height of a paragraph in points (wrapped lines × line height + spacing).
     *
     * @param  array<string, mixed>  $paragraph
     */
    private static function paragraphHeight(array $paragraph): float
    {
        $size = ((int) ($paragraph['size'] ?? 1600)) / 100;
        $indent = (int) ($paragraph['level'] ?? 0) * self::LEVEL_INDENT + (! empty($paragraph['bullet']) ? self::BULLET_INDENT : 0);
        $width = self::TEXT_WIDTH - 2 * self::TEXT_INSET_X - $indent;
        $textWidth = mb_strlen(($paragraph['lead'] ?? '').$paragraph['text']) * $size * self::AVERAGE_CHAR_WIDTH;
        $lines = max(1, (int) ceil($textWidth / $width));

        return $lines * $size * self::LINE_HEIGHT + self::PARAGRAPH_SPACING;
    }

    /**
     * @param  list<string>|string  $value
     * @return list<string>
     */
    private static function lines(array|string $value): array
    {
        $lines = is_array($value) ? $value : (preg_split('/\R/', $value) ?: []);

        return array_values(array_filter(array_map(fn ($line): string => trim((string) $line), $lines), fn (string $line): bool => $line !== ''));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function goalParagraphs(string $goal): array
    {
        $goal = trim($goal);

        return $goal === '' ? [] : [
            ['text' => 'Tujuan', 'bold' => true, 'size' => 2000, 'keep' => true],
            ['text' => $goal, 'size' => 1800],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $menu
     * @return list<array<string, mixed>>
     */
    private static function menuParagraphs(array $menu): array
    {
        $paragraphs = [];
        foreach ($menu as $node) {
            $paragraphs[] = self::labelled((string) ($node['name'] ?? ''), (string) ($node['description'] ?? ''), 0);
            foreach ($node['children'] ?? [] as $child) {
                $paragraphs[] = self::labelled((string) ($child['name'] ?? ''), (string) ($child['description'] ?? ''), 1);
            }
        }

        return $paragraphs === [] ? [] : [['text' => 'Struktur Menu Navigasi', 'bold' => true, 'size' => 2000, 'keep' => true], ...$paragraphs];
    }

    /**
     * @return array<string, mixed>
     */
    private static function labelled(string $name, string $description, int $level): array
    {
        $description = trim($description);

        return $description === ''
            ? ['text' => $name, 'level' => $level, 'bullet' => true, 'size' => 1800]
            : ['lead' => $name.': ', 'text' => $description, 'level' => $level, 'bullet' => true, 'size' => 1800];
    }

    /**
     * @param  list<array<string, mixed>>  $mockups
     * @return list<array{path: string, caption: string}>
     */
    private static function mockups(array $mockups): array
    {
        $disk = Storage::disk('local');
        $result = [];
        foreach ($mockups as $mockup) {
            $path = (string) ($mockup['path'] ?? '');
            if ($path !== '' && $disk->exists($path)) {
                $result[] = ['path' => $disk->path($path), 'caption' => (string) ($mockup['caption'] ?? '')];
            }
        }

        return $result;
    }

    /**
     * @param  list<array<string, mixed>>  $features
     * @return list<array<string, mixed>>
     */
    private static function websiteFeatureParagraphs(array $features): array
    {
        $checked = array_values(array_filter($features, fn (array $f): bool => (bool) ($f['checked'] ?? false)));
        usort($checked, fn (array $a, array $b): int => ($a['sort'] ?? 0) <=> ($b['sort'] ?? 0));

        return array_map(fn (array $f, int $i): array => [
            'lead' => ($i + 1).'. '.$f['name'].'. ',
            'text' => (string) ($f['description'] ?? ''),
            'size' => 1800,
        ], $checked, array_keys($checked));
    }

    /**
     * @param  list<array<string, mixed>>  $modules
     * @return list<array<string, mixed>>
     */
    private static function moduleParagraphs(array $modules): array
    {
        $paragraphs = [];
        foreach ($modules as $i => $module) {
            $paragraphs[] = ['text' => ($i + 1).'. '.$module['name'], 'bold' => true, 'size' => 2000, 'keep' => true];
            if (trim((string) ($module['description'] ?? '')) !== '') {
                $paragraphs[] = ['text' => trim((string) $module['description']), 'level' => 1, 'size' => 1800];
            }
            foreach ($module['children'] ?? [] as $child) {
                $paragraphs[] = self::labelled((string) $child['name'], (string) ($child['description'] ?? ''), 1);
            }
        }

        return $paragraphs;
    }
}
