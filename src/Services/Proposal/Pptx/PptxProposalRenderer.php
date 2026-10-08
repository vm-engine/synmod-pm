<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services\Proposal\Pptx;

use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

/**
 * Fills the template deck with proposal data (spec §6.2).
 */
final class PptxProposalRenderer
{
    private const BLUE_HEADER = '4472C4';

    private const BLUE_BODY = 'D9E1F2';

    public function __construct(private readonly PptxGanttRenderer $gantt) {}

    public function render(ProposalDocumentData $data, string $outputPath): void
    {
        $package = PptxPackage::fromTemplate(TemplateMap::path(), $outputPath);

        $this->cover($package, $data);
        $this->wording($package, $data);
        $this->textPages($package, TemplateMap::GOAL, $data->goalPages);
        $this->textPages($package, TemplateMap::MENU, $data->menuPages);
        $this->mockups($package, $data);
        $this->features($package, $data);
        $this->fill($package, TemplateMap::COSTING, $this->costing($data));
        $this->fill($package, TemplateMap::HOSTING, $this->hosting($data));
        $this->timeline($package, $data);
        $this->fill($package, TemplateMap::DELIVERABLES, $this->deliverables($data));
        $this->fill($package, TemplateMap::AFTER_SALES, $this->afterSales($data));

        $package->save();
    }

    private function fill(PptxPackage $package, int $slide, string $shapes): void
    {
        $part = $package->slidePart($slide);
        $dom = $package->dom($part);
        PptxSlide::replaceBody($dom, $shapes);
        $package->writeDom($part, $dom);
    }

    private function cover(PptxPackage $package, ProposalDocumentData $data): void
    {
        $part = $package->slidePart(TemplateMap::COVER);
        $dom = $package->dom($part);
        PptxSlide::setParagraphText($dom, 'TextBox 5', 1, $data->title);
        PptxSlide::setParagraphText($dom, 'Rectangle 6', 0, $data->subject);
        PptxSlide::setParagraphText($dom, 'TextBox 7', 0, 'No : '.$data->number);
        $package->writeDom($part, $dom);
    }

    private function wording(PptxPackage $package, ProposalDocumentData $data): void
    {
        $divider = $package->dom($package->slidePart(TemplateMap::DIVIDER));
        PptxSlide::replaceText($divider, 'Web Development', $data->solutionLabel);
        $package->writeDom($package->slidePart(TemplateMap::DIVIDER), $divider);

        $background = $package->dom($package->slidePart(TemplateMap::BACKGROUND));
        PptxSlide::replaceText($background, ProposalDocumentData::WEBSITE_PHRASE, $data->backgroundPhrase);
        $package->writeDom($package->slidePart(TemplateMap::BACKGROUND), $background);
    }

    /**
     * One slide per page of paragraphs: the template slide is cloned for overflow pages, so every
     * page keeps the same title. No pages removes the slide.
     *
     * @param  list<list<array<string, mixed>>>  $pages
     */
    private function textPages(PptxPackage $package, int $slide, array $pages): void
    {
        if ($pages === []) {
            $package->removeSlide($slide);

            return;
        }

        $slides = [$slide];
        for ($i = 1; $i < count($pages); $i++) {
            $slides[] = $package->cloneSlide($slide, $slides[$i - 1]);
        }
        foreach ($pages as $i => $paragraphs) {
            $this->fill($package, $slides[$i], (new PptxXml)->textBox(PptxXml::TEXT_X, PptxXml::TEXT_Y, PptxXml::TEXT_CX, PptxXml::TEXT_CY, $paragraphs));
        }
    }

    private function mockups(PptxPackage $package, ProposalDocumentData $data): void
    {
        if ($data->mockups === []) {
            $package->removeSlide(TemplateMap::MOCKUP);

            return;
        }

        $slides = [TemplateMap::MOCKUP];
        for ($i = 1; $i < count($data->mockups); $i++) {
            $slides[] = $package->cloneSlide(TemplateMap::MOCKUP, $slides[$i - 1]);
        }

        foreach ($data->mockups as $i => $mockup) {
            $rId = $package->addImage($slides[$i], $mockup['path']);
            [$cx, $cy] = $this->fit($mockup['path'], PptxXml::BODY_CX, PptxXml::BODY_CY);
            $x = PptxXml::BODY_X + intdiv(PptxXml::BODY_CX - $cx, 2);
            $this->fill($package, $slides[$i], (new PptxXml)->picture($rId, $x, PptxXml::BODY_Y, $cx, $cy));

            $part = $package->slidePart($slides[$i]);
            $dom = $package->dom($part);
            PptxSlide::setParagraphText($dom, 'Title 1', 0, $mockup['caption'] !== '' ? 'Layout - '.$mockup['caption'] : 'Layout');
            $package->writeDom($part, $dom);
        }
    }

    private function features(PptxPackage $package, ProposalDocumentData $data): void
    {
        $package->removeSlide(TemplateMap::FEATURES_EXTRA);
        $this->textPages($package, TemplateMap::FEATURES, $data->featurePages);
    }

    private function costing(ProposalDocumentData $data): string
    {
        $xml = new PptxXml;
        $rows = [];
        foreach ($data->costing as $row) {
            $rows[] = [['text' => $row['section'], 'bold' => true], $row['features'], ['text' => $row['price'], 'align' => 'r']];
        }
        $rows[] = ['', ['text' => 'GRAND TOTAL', 'bold' => true, 'align' => 'r'], ['text' => $data->grandTotalLabel, 'bold' => true, 'align' => 'r']];
        $rows[] = [['text' => '“'.$data->grandTotalWords.'”', 'span' => 3, 'bold' => true, 'align' => 'ctr']];

        return $xml->textBox(PptxXml::BODY_X, PptxXml::BODY_Y, 6000000, 400000, [['text' => $data->developmentLabel, 'bold' => true, 'size' => 1600]])
            .$xml->table(PptxXml::BODY_X, PptxXml::BODY_Y + 450000, [2700000, 6263131, 2500000], ['SECTION', 'MODULE / FEATURE', 'PRICE'], $rows)
            .$this->taxNote($xml, $data);
    }

    private function hosting(ProposalDocumentData $data): string
    {
        $xml = new PptxXml;
        $rows = [];
        foreach ($data->hosting as $i => $row) {
            $rows[] = [['text' => (string) ($i + 1), 'align' => 'ctr'], $row['name'], $row['specs'], ['text' => $row['price'], 'align' => 'ctr']];
        }
        $shapes = $data->hostingOptional
            ? $xml->textBox(PptxXml::BODY_X, PptxXml::BODY_Y, 6000000, 400000, [['text' => 'Hosting dan Domain (opsional)', 'bold' => true, 'size' => 1600]])
            : '';

        return $shapes
            .$xml->table(PptxXml::BODY_X, PptxXml::BODY_Y + 450000, [600000, 3500000, 4863131, 2500000], ['No', 'SECTION', 'MODULE / FEATURE', 'PRICE'], $rows)
            .$this->taxNote($xml, $data);
    }

    private function timeline(PptxPackage $package, ProposalDocumentData $data): void
    {
        $pages = count($data->gantt->pages);
        if ($pages === 0) {
            $package->removeSlide(TemplateMap::TIMELINE);

            return;
        }

        $slides = [TemplateMap::TIMELINE];
        for ($i = 1; $i < $pages; $i++) {
            $slides[] = $package->cloneSlide(TemplateMap::TIMELINE, $slides[$i - 1]);
        }
        foreach ($slides as $page => $slide) {
            $xml = new PptxXml;
            $shapes = $this->gantt->render($data->gantt, $page, $xml)
                .$xml->textBox(PptxXml::BODY_X, PptxXml::BODY_Y + PptxXml::BODY_CY - 450000, 6000000, 450000, [
                    ['text' => 'Keterangan:', 'bold' => true, 'size' => 1200],
                    ['text' => $data->workdayNote, 'bullet' => true, 'size' => 1200],
                ]);
            $this->fill($package, $slide, $shapes);
        }
    }

    private function deliverables(ProposalDocumentData $data): string
    {
        $rows = array_map(fn (array $row): array => [$row['item'], $row['description']], $data->deliverables);

        return (new PptxXml)->table(PptxXml::BODY_X, PptxXml::BODY_Y, [5731565, 5731566], ['Item', 'Description'], $rows, [
            'header_fill' => self::BLUE_HEADER,
            'body_fill' => self::BLUE_BODY,
            'border' => 'FFFFFF',
        ]);
    }

    private function afterSales(ProposalDocumentData $data): string
    {
        $xml = new PptxXml;
        $shapes = '';
        $width = 3600000;
        $gap = intdiv(PptxXml::BODY_CX - 3 * $width, 2);
        foreach ($data->afterSales as $i => $label) {
            $x = PptxXml::BODY_X + ($i % 3) * ($width + $gap);
            $y = PptxXml::BODY_Y + intdiv($i, 3) * 1000000;
            $shapes .= $xml->rect($x, $y, $width, 800000, self::BLUE_BODY, 'roundRect', $label, 1600, 'ctr', '1F3864', true);
        }

        return $shapes;
    }

    private function taxNote(PptxXml $xml, ProposalDocumentData $data): string
    {
        if (! $data->taxNote) {
            return '';
        }

        return $xml->textBox(PptxXml::BODY_X, PptxXml::BODY_Y + PptxXml::BODY_CY - 600000, 6000000, 600000, [
            ['text' => 'Keterangan:', 'bold' => true, 'size' => 1400],
            ['text' => 'Harga belum termasuk Pajak', 'bullet' => true, 'size' => 1400],
        ]);
    }

    /**
     * Fit an image inside a box, keeping its aspect ratio.
     *
     * @return array{0: int, 1: int}
     */
    private function fit(string $path, int $maxWidth, int $maxHeight): array
    {
        $size = @getimagesize($path);
        if ($size === false || $size[0] === 0 || $size[1] === 0) {
            return [$maxWidth, $maxHeight];
        }
        $scale = min($maxWidth / $size[0], $maxHeight / $size[1]);

        return [(int) ($size[0] * $scale), (int) ($size[1] * $scale)];
    }
}
