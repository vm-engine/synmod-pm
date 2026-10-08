<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Pptx\PptxPackage;
use VmEngine\Pm\Services\Proposal\Pptx\PptxProposalRenderer;
use VmEngine\Pm\Services\Proposal\Pptx\PptxSlide;
use VmEngine\Pm\Services\Proposal\Pptx\TemplateMap;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

/**
 * @return array{0: PptxPackage, 1: list<int>}
 */
function renderPptx(ProjectProposal $proposal): array
{
    $out = tempnam(sys_get_temp_dir(), 'out').'.pptx';
    app(PptxProposalRenderer::class)->render(ProposalDocumentData::build($proposal), $out);
    $package = PptxPackage::fromTemplate($out, tempnam(sys_get_temp_dir(), 'chk').'.pptx');

    return [$package, $package->slideOrder()];
}

function slideText(PptxPackage $package, int $slide): string
{
    return PptxSlide::text($package->dom($package->slidePart($slide)));
}

test('an application proposal produces the 26-slide PT ABS structure', function () {
    $proposal = ProjectProposal::factory()->create(['number' => '001/PRJ/VMC/1-NC/I/2026', 'subject' => 'PT ANUGERAH BERKAT SARANA']);

    [$package, $order] = renderPptx($proposal);

    expect($order)->toHaveCount(26)
        ->and($order)->not->toContain(TemplateMap::GOAL)
        ->and($order)->not->toContain(TemplateMap::MENU)
        ->and($order)->not->toContain(TemplateMap::MOCKUP)
        ->and($order)->not->toContain(TemplateMap::FEATURES_EXTRA)
        ->and(slideText($package, TemplateMap::COVER))->toContain('No : 001/PRJ/VMC/1-NC/I/2026')
        ->and(slideText($package, TemplateMap::COVER))->toContain('PT ANUGERAH BERKAT SARANA')
        ->and(slideText($package, TemplateMap::COVER))->not->toContain('[DRAFT]')
        ->and(slideText($package, TemplateMap::DIVIDER))->toContain('Application Development')
        ->and(slideText($package, TemplateMap::BACKGROUND))->toContain(ProposalDocumentData::APPLICATION_PHRASE)
        ->and(slideText($package, TemplateMap::FEATURES))->toContain('Warehouse Management')
        ->and(slideText($package, TemplateMap::COSTING))->toContain('DUA RATUS DUA PULUH JUTA RUPIAH')
        ->and(slideText($package, TemplateMap::COSTING))->toContain('Harga belum termasuk Pajak')
        ->and(slideText($package, TemplateMap::HOSTING))->toContain('Rp18.000.000/tahun')
        ->and(slideText($package, TemplateMap::TIMELINE))->toContain('Development (Programming) / Implementation')
        ->and(slideText($package, TemplateMap::DELIVERABLES))->toContain('Layanan dukungan 3 bulan (free maintenance)')
        ->and(slideText($package, TemplateMap::AFTER_SALES))->toContain('Error/Bug Fix');
});

test('the costing table has one row per section plus total and words rows', function () {
    [$package] = renderPptx(ProjectProposal::factory()->create());
    $xml = $package->read($package->slidePart(TemplateMap::COSTING));

    // header + 2 sections + grand total + words
    expect(substr_count($xml, '<a:tr '))->toBe(5);
});

test('an unpublished cover has no draft marker and shows the next number', function () {
    dbconf(['pm.documents.PROPOSAL.number_format' => '{INDEX}/{PROJECT_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
    [$package] = renderPptx(ProjectProposal::factory()->create());
    $cover = slideText($package, TemplateMap::COVER);

    expect($cover)->not->toContain('DRAFT')
        ->and($cover)->toContain('PROPOSAL PENAWARAN')
        ->and($cover)->toMatch('#No : 001/#');
});

test('a website proposal keeps scope slides and adds one slide per mockup', function () {
    Storage::fake('local');
    Storage::disk('local')->put('m/home.png', (string) file_get_contents(TemplateMap::asset('logo.png')));
    Storage::disk('local')->put('m/about.png', (string) file_get_contents(TemplateMap::asset('logo.png')));
    $proposal = ProjectProposal::factory()->website()->create();
    $proposal->update(['scope' => [
        'goal' => 'Arsip tulisan.',
        'menu' => [['id' => 'a', 'name' => 'Beranda', 'description' => 'Hero', 'children' => []]],
        'mockups' => [['path' => 'm/home.png', 'caption' => 'Home'], ['path' => 'm/about.png', 'caption' => 'About']],
    ]]);

    [$package, $order] = renderPptx($proposal->fresh());

    // two mockups (+1 slide) and 8 features over two Feature & Module slides (+1), minus slide 21
    expect($order)->toHaveCount(31)
        ->and(slideText($package, TemplateMap::GOAL))->toContain('Arsip tulisan.')
        ->and(slideText($package, TemplateMap::MENU))->toContain('Beranda')
        ->and(slideText($package, TemplateMap::MOCKUP))->toContain('Layout - Home')
        ->and(slideText($package, TemplateMap::DIVIDER))->toContain('Web Development')
        ->and(slideText($package, TemplateMap::FEATURES))->toContain('Modul Static Page');
    $featuresIndex = array_search(TemplateMap::FEATURES, $order, true);
    expect(slideText($package, $order[$featuresIndex + 1]))->toContain('Feature & Module')
        ->and(slideText($package, $order[$featuresIndex + 1]))->toContain('Fitur Multi-Bahasa');
    $mockupIndex = array_search(TemplateMap::MOCKUP, $order, true);
    expect(slideText($package, $order[$mockupIndex + 1]))->toContain('Layout - About');
});

test('more than fourteen tasks clone the timeline slide', function () {
    $tasks = [];
    for ($i = 1; $i <= 20; $i++) {
        $tasks[] = ['id' => 't'.$i, 'name' => 'Task '.$i, 'parent_id' => null, 'duration' => 1, 'predecessor_id' => $i > 1 ? 't'.($i - 1) : null];
    }
    $proposal = ProjectProposal::factory()->create(['timeline' => ['start_date' => '2026-01-19', 'extra_holidays' => [], 'worked_holidays' => [], 'tasks' => $tasks]]);

    [$package, $order] = renderPptx($proposal);
    $timelineIndex = array_search(TemplateMap::TIMELINE, $order, true);

    expect($order)->toHaveCount(27)
        ->and(slideText($package, $order[$timelineIndex + 1]))->toContain('Task 20');
});
