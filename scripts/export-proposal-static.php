<?php

declare(strict_types=1);

/**
 * Regenerates resources/templates/proposal/static/*.jpg from the template deck.
 * Run from the main project root: php packages/synmod-pm/scripts/export-proposal-static.php
 * Requires `soffice` and `pdftoppm` (dev machine only).
 */

use VmEngine\Pm\Services\Proposal\Pptx\PptxPackage;
use VmEngine\Pm\Services\Proposal\Pptx\PptxSlide;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

require getcwd().'/vendor/autoload.php';

$work = sys_get_temp_dir().'/proposal-static-'.getmypid();
@mkdir($work, 0775, true);
$staticDir = TemplateMap::asset('static');
@mkdir($staticDir, 0775, true);

foreach (['website', 'application'] as $variant) {
    $pptx = "{$work}/{$variant}.pptx";
    $package = PptxPackage::fromTemplate(TemplateMap::path(), $pptx);

    $cover = $package->dom($package->slidePart(TemplateMap::COVER));
    PptxSlide::setParagraphText($cover, 'TextBox 5', 0, '');
    PptxSlide::setParagraphText($cover, 'TextBox 5', 1, '');
    PptxSlide::setParagraphText($cover, 'Rectangle 6', 0, '');
    PptxSlide::setParagraphText($cover, 'TextBox 7', 0, '');
    $package->writeDom($package->slidePart(TemplateMap::COVER), $cover);

    if ($variant === 'application') {
        $divider = $package->dom($package->slidePart(TemplateMap::DIVIDER));
        PptxSlide::replaceText($divider, 'Web Development', 'Application Development');
        $package->writeDom($package->slidePart(TemplateMap::DIVIDER), $divider);

        $background = $package->dom($package->slidePart(TemplateMap::BACKGROUND));
        PptxSlide::replaceText($background, ProposalDocumentData::WEBSITE_PHRASE, ProposalDocumentData::APPLICATION_PHRASE);
        $package->writeDom($package->slidePart(TemplateMap::BACKGROUND), $background);
    }
    $package->save();

    passthru(sprintf('cd %s && soffice --headless --convert-to pdf %s >/dev/null 2>&1', escapeshellarg($work), escapeshellarg(basename($pptx))), $code);
    if ($code !== 0) {
        fwrite(STDERR, "soffice failed for {$variant}\n");
        exit(1);
    }
    passthru(sprintf('pdftoppm -jpeg -jpegopt quality=82 -scale-to-x 1600 -scale-to-y -1 %s %s', escapeshellarg("{$work}/{$variant}.pdf"), escapeshellarg("{$work}/{$variant}")), $code);
    if ($code !== 0) {
        fwrite(STDERR, "pdftoppm failed for {$variant}\n");
        exit(1);
    }

    $page = fn (int $n): string => sprintf('%s/%s-%02d.jpg', $work, $variant, $n);
    copy($page(TemplateMap::DIVIDER), sprintf('%s/slide-12-%s.jpg', $staticDir, $variant));
    copy($page(TemplateMap::BACKGROUND), sprintf('%s/slide-13-%s.jpg', $staticDir, $variant));
    if ($variant === 'website') {
        copy($page(TemplateMap::COVER), $staticDir.'/slide-01-cover.jpg');
        foreach (TemplateMap::STATIC_PDF_SLIDES as $n) {
            copy($page($n), sprintf('%s/slide-%02d.jpg', $staticDir, $n));
        }
    }
}

echo "Static slides written to {$staticDir}\n";
