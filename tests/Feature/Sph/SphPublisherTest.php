<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Sph\PdfSphRenderer;
use VmEngine\Pm\Services\Sph\SphPublisher;

use function Pest\Laravel\mock;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('first publish assigns an SPH number and stores version 1 with pdf and docx', function () {
    $sph = ProjectSph::factory()->create();

    $document = app(SphPublisher::class)->publish($sph, $this->user->id);
    $sph->refresh();

    expect($sph->number)->toStartWith('001/')->toContain('/SPH/')
        ->and($sph->status)->toBe(ProposalStatus::Published)
        ->and($sph->published_version)->toBe(1)
        ->and($sph->has_unpublished_changes)->toBeFalse()
        ->and($document->type)->toBe(Documents::SPH)
        ->and($document->number)->toBe($sph->number)
        ->and(Storage::disk('local')->exists($document->file_path))->toBeTrue()
        ->and(Storage::disk('local')->exists($document->file_info['docx']))->toBeTrue()
        ->and((float) $sph->project->fresh()->nominal)->toBe(20000000.0);
});

test('republishing keeps the number and adds version 2', function () {
    $sph = ProjectSph::factory()->create();
    $publisher = app(SphPublisher::class);
    $publisher->publish($sph, $this->user->id);
    $number = $sph->fresh()->number;

    $sph->fresh()->update(['need' => 'Revised']);
    $second = $publisher->publish($sph->fresh(), $this->user->id);

    expect($sph->fresh()->number)->toBe($number)
        ->and($second->version)->toBe(2);
});

test('an invalid SPH stores nothing', function () {
    $sph = ProjectSph::factory()->create(['items' => []]);

    expect(fn () => app(SphPublisher::class)->publish($sph, $this->user->id))->toThrow(ProposalValidationException::class)
        ->and(ProjectDocument::query()->count())->toBe(0);
});

test('a render failure rolls back and deletes written files', function () {
    $sph = ProjectSph::factory()->create();
    mock(PdfSphRenderer::class)->shouldReceive('render')->andThrow(new RuntimeException('boom'));

    expect(fn () => app(SphPublisher::class)->publish($sph, $this->user->id))->toThrow(RuntimeException::class)
        ->and(ProjectDocument::query()->count())->toBe(0)
        ->and($sph->fresh()->number)->toBeNull()
        ->and(Storage::disk('local')->allFiles())->toBe([]);
});
