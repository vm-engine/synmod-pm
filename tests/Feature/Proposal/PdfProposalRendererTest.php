<?php

declare(strict_types=1);

use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\PdfProposalRenderer;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

function pdfPageCount(string $pdf): int
{
    return preg_match_all('#/Type\s*/Page(?!s)#', $pdf);
}

test('an application proposal renders 26 pages', function () {
    $pdf = app(PdfProposalRenderer::class)->render(ProposalDocumentData::build(ProjectProposal::factory()->create()));

    expect(substr($pdf, 0, 4))->toBe('%PDF')
        ->and(pdfPageCount($pdf))->toBe(26);
});

test('a website proposal with a goal adds the goal page', function () {
    $proposal = ProjectProposal::factory()->website()->create();
    $proposal->update(['scope' => ['goal' => 'Arsip.', 'menu' => [], 'mockups' => []]]);

    $pdf = app(PdfProposalRenderer::class)->render(ProposalDocumentData::build($proposal->fresh()));

    // 8 website features need two Feature & Module pages, like the original Think deck
    expect(pdfPageCount($pdf))->toBe(28);
});

test('licensed fonts in the font folder are preferred, with open fallbacks', function () {
    $dir = sys_get_temp_dir().'/proposal-fonts-'.uniqid();
    mkdir($dir);

    $fallback = PdfProposalRenderer::fonts($dir);
    expect(basename($fallback['body']['regular']))->toBe('Carlito-Regular.ttf')
        ->and(basename($fallback['rounded']))->toBe('VarelaRound-Regular.ttf');

    foreach (['calibri.ttf', 'calibrib.ttf', 'CALIBRII.TTF', 'calibriz.ttf', 'ARLRDBD.TTF'] as $file) {
        touch($dir.'/'.$file);
    }
    $licensed = PdfProposalRenderer::fonts($dir);
    expect(basename($licensed['body']['regular']))->toBe('calibri.ttf')
        ->and(basename($licensed['body']['bold']))->toBe('calibrib.ttf')
        ->and(basename($licensed['body']['italic']))->toBe('CALIBRII.TTF')
        ->and(basename($licensed['body']['bolditalic']))->toBe('calibriz.ttf')
        ->and(basename($licensed['rounded']))->toBe('ARLRDBD.TTF');
});
