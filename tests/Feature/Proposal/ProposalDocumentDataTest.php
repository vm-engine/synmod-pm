<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\ProposalDocumentData;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('application proposal data matches the PT ABS deck', function () {
    $proposal = ProjectProposal::factory()->create(['number' => '001/PRJ/VMC/534-NC/I/2026', 'maintenance_months' => 6]);

    $data = ProposalDocumentData::build($proposal);

    expect($data->number)->toBe('001/PRJ/VMC/534-NC/I/2026')
        ->and($data->grandTotalLabel)->toBe('Rp220.000.000,-')
        ->and($data->grandTotalWords)->toBe('DUA RATUS DUA PULUH JUTA RUPIAH')
        ->and($data->solutionLabel)->toBe('Application Development')
        ->and($data->developmentLabel)->toBe('Application Development')
        ->and($data->backgroundPhrase)->toBe(ProposalDocumentData::APPLICATION_PHRASE)
        ->and($data->costing[0]['price'])->toBe('Rp60.000.000,-')
        ->and($data->hosting[0]['price'])->toBe('Rp18.000.000/tahun')
        ->and($data->hosting[1]['price'])->toBe('Rp400.000/tahun *start from')
        ->and($data->hostingOptional)->toBeTrue()
        ->and($data->afterSales[0])->toBe('Free Maintenance Service 6 Bulan')
        ->and($data->deliverables[3]['description'])->toBe('Layanan dukungan 6 bulan (free maintenance)')
        ->and($data->gantt->scale)->toBe('week')
        ->and($data->goalPages)->toBe([])
        ->and($data->featurePages)->toHaveCount(1)
        ->and($data->featurePages[0][0])->toMatchArray(['text' => '1. Master Data', 'bold' => true]);
});

test('an unpublished proposal shows the next document number without consuming it', function () {
    dbconf(['pm.documents.PROPOSAL.number_format' => '{INDEX}/{PROJECT_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
    $proposal = ProjectProposal::factory()->create();
    $expected = Documents::PROPOSAL->handler()->generateDocumentNumber($proposal->project, Documents::PROPOSAL);

    $first = ProposalDocumentData::build($proposal);
    $second = ProposalDocumentData::build($proposal->fresh());

    expect($first->number)->toBe($expected)
        ->and($first->number)->toStartWith('001/')
        ->and($second->number)->toBe($expected)
        ->and($proposal->fresh()->number)->toBeNull();
});

test('website data uses only checked features and the scope blocks', function () {
    Storage::fake('local');
    Storage::disk('local')->put('mockups/home.png', 'png-bytes');
    $proposal = ProjectProposal::factory()->website()->create();
    $features = $proposal->features;
    $features[0]['checked'] = false;
    $proposal->update([
        'features' => $features,
        'scope' => [
            'goal' => 'Arsip tulisan.',
            'menu' => [['id' => 'm', 'name' => 'Tentang', 'description' => '', 'children' => [['id' => 'c', 'name' => 'Tentang Saya', 'description' => 'Profil']]]],
            'mockups' => [['path' => 'mockups/home.png', 'caption' => 'Home'], ['path' => 'mockups/missing.png', 'caption' => 'X']],
        ],
    ]);

    $data = ProposalDocumentData::build($proposal->fresh());
    $allFeatureText = collect($data->featurePages)->flatten(1)->pluck('lead')->implode(' ');

    expect($data->solutionLabel)->toBe('Web Development')
        ->and($allFeatureText)->not->toContain('Modul Static Page')
        ->and($allFeatureText)->toContain('1. Modul Dynamic Page.')
        ->and($data->goalPages[0][1]['text'])->toBe('Arsip tulisan.')
        ->and($data->menuPages[0])->toHaveCount(3)
        ->and($data->menuPages[0][2])->toMatchArray(['lead' => 'Tentang Saya: ', 'text' => 'Profil', 'level' => 1])
        ->and($data->mockups)->toHaveCount(1)
        ->and($data->mockups[0]['caption'])->toBe('Home')
        ->and($data->gantt->scale)->toBe('day');
});

test('workday note mentions holidays only when one falls inside the timeline', function () {
    $proposal = ProjectProposal::factory()->create();
    expect(ProposalDocumentData::build($proposal)->workdayNote)->toBe('Hari kerja: Senin - Jumat');

    Holiday::create(['date' => '2026-03-20', 'name' => 'Idul Fitri']);
    expect(ProposalDocumentData::build($proposal)->workdayNote)->toBe('Hari kerja: Senin - Jumat, di luar hari libur');
});

test('paginate fills a page up to the text box height', function () {
    $short = array_fill(0, 30, ['text' => 'short', 'size' => 1800]);

    expect(array_map('count', ProposalDocumentData::paginate($short)))->toBe([12, 12, 6]);
});

test('paginate counts wrapped lines of long paragraphs', function () {
    $long = array_fill(0, 3, ['text' => str_repeat('panjang ', 75), 'size' => 1800]);

    expect(array_map('count', ProposalDocumentData::paginate($long)))->toBe([2, 1]);
});

test('a heading never ends a page', function () {
    $paragraphs = [...array_fill(0, 11, ['text' => 'item', 'size' => 1800]), ['text' => '2. Inventory', 'bold' => true, 'size' => 1800, 'keep' => true], ['text' => 'Stock In', 'size' => 1800]];

    $pages = ProposalDocumentData::paginate($paragraphs);

    expect(array_map('count', $pages))->toBe([11, 2])
        ->and($pages[1][0]['text'])->toBe('2. Inventory');
});
