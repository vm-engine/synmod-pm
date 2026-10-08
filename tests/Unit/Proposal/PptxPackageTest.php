<?php

declare(strict_types=1);

use VmEngine\Pm\Services\Proposal\Pptx\PptxPackage;
use VmEngine\Pm\Services\Proposal\Pptx\PptxSlide;
use VmEngine\Pm\Services\Proposal\Pptx\PptxXml;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;

function workCopy(): string
{
    return tempnam(sys_get_temp_dir(), 'pptx').'.pptx';
}

/** Re-open a saved file and return its slide order. */
function reopenedOrder(string $path): array
{
    return PptxPackage::fromTemplate($path, workCopy())->slideOrder();
}

test('the template has thirty slides in order', function () {
    $package = PptxPackage::fromTemplate(TemplateMap::path(), workCopy());

    expect($package->slideOrder())->toBe(range(1, 30));
});

test('removing a slide drops it from the order, the content types and the zip', function () {
    $path = workCopy();
    $package = PptxPackage::fromTemplate(TemplateMap::path(), $path);
    $package->removeSlide(TemplateMap::FEATURES_EXTRA);
    $package->save();

    $zip = new ZipArchive;
    $zip->open($path);
    expect(reopenedOrder($path))->not->toContain(21)
        ->and($zip->locateName('ppt/slides/slide21.xml'))->toBeFalse()
        ->and($zip->getFromName('[Content_Types].xml'))->not->toContain('/ppt/slides/slide21.xml');
});

test('cloning inserts the copy right after the given slide', function () {
    $path = workCopy();
    $package = PptxPackage::fromTemplate(TemplateMap::path(), $path);
    $new = $package->cloneSlide(TemplateMap::TIMELINE, TemplateMap::TIMELINE);
    $package->save();

    $order = reopenedOrder($path);
    expect($new)->toBe(31)
        ->and(array_slice($order, array_search(24, $order, true), 2))->toBe([24, 31]);

    $zip = new ZipArchive;
    $zip->open($path);
    expect($zip->getFromName('ppt/slides/_rels/slide31.xml.rels'))->not->toContain('notesSlide');
});

test('replaceBody keeps title, red bar and logo and appends new shapes', function () {
    $package = PptxPackage::fromTemplate(TemplateMap::path(), workCopy());
    $dom = $package->dom($package->slidePart(TemplateMap::COSTING));

    PptxSlide::replaceBody($dom, (new PptxXml)->textBox(PptxXml::BODY_X, PptxXml::BODY_Y, 1000, 1000, [['text' => 'Hello & bye']]));

    $text = PptxSlide::text($dom);
    expect($text)->toContain('Project Costing')
        ->and($text)->toContain('Hello & bye')
        ->and($text)->not->toContain('LIMA BELAS JUTA')
        ->and($dom->getElementsByTagNameNS('http://schemas.openxmlformats.org/presentationml/2006/main', 'pic')->length)->toBe(1);
});

test('cover paragraphs can be replaced', function () {
    $package = PptxPackage::fromTemplate(TemplateMap::path(), workCopy());
    $dom = $package->dom($package->slidePart(TemplateMap::COVER));

    PptxSlide::setParagraphText($dom, 'TextBox 7', 0, 'No : 001/PRJ/VMC/1-NC/I/2026');
    PptxSlide::setParagraphText($dom, 'Rectangle 6', 0, 'PT ABC');

    expect(PptxSlide::text($dom))->toContain('No : 001/PRJ/VMC/1-NC/I/2026')
        ->and(PptxSlide::text($dom))->toContain('PT ABC')
        ->and(PptxSlide::text($dom))->not->toContain('083/WEB');
});

test('replaceText swaps the background phrase', function () {
    $package = PptxPackage::fromTemplate(TemplateMap::path(), workCopy());
    $dom = $package->dom($package->slidePart(TemplateMap::BACKGROUND));

    expect(PptxSlide::replaceText($dom, 'memiliki sebuah website yang informatif, dinamis, professional dan elegant', 'X-PHRASE'))->toBe(1)
        ->and(PptxSlide::text($dom))->toContain('X-PHRASE.')
        ->and(PptxSlide::text($dom))->toContain('Four Vision Media');
});

test('table xml has one row per data row plus header and supports spans', function () {
    $xml = (new PptxXml)->table(0, 0, [100, 100, 100], ['A', 'B', 'C'], [
        ['x', ['l1', 'l2'], 'z'],
        [['text' => 'merged', 'span' => 3, 'bold' => true, 'align' => 'ctr']],
    ]);

    expect(substr_count($xml, '<a:tr '))->toBe(3)
        ->and($xml)->toContain('gridSpan="3"')
        ->and(substr_count($xml, 'hMerge="1"'))->toBe(2)
        ->and(substr_count($xml, '<a:buChar'))->toBe(2);
});

test('addImage stores the media and returns a relationship id', function () {
    $png = tempnam(sys_get_temp_dir(), 'img').'.png';
    copy(TemplateMap::asset('logo.png'), $png);
    $path = workCopy();
    $package = PptxPackage::fromTemplate(TemplateMap::path(), $path);
    $rId = $package->addImage(TemplateMap::MOCKUP, $png);
    $package->save();

    $zip = new ZipArchive;
    $zip->open($path);
    expect($rId)->toStartWith('rId')
        ->and($zip->getFromName('ppt/slides/_rels/slide16.xml.rels'))->toContain('../media/proposal1.png');
});
