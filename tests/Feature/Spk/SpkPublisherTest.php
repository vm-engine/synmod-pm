<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Spk\SpkPublisher;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('publish stores pdf + docx as the next SPK version and numbers the SPK once', function () {
    $spk = ProjectSpk::factory()->create();

    $first = app(SpkPublisher::class)->publish($spk, $this->user->id);
    $second = app(SpkPublisher::class)->publish($spk->fresh(), $this->user->id);

    $spk->refresh();
    expect($first->type)->toBe(Documents::SPK)
        ->and($first->version)->toBe(1)
        ->and($second->version)->toBe(2)
        ->and($second->number)->toBe($first->number)
        ->and(Storage::disk('local')->exists($first->file_path))->toBeTrue()
        ->and(Storage::disk('local')->exists($first->file_info['docx']))->toBeTrue()
        ->and($spk->isPublished())->toBeTrue()
        ->and($spk->published_version)->toBe(2)
        ->and($spk->has_unpublished_changes)->toBeFalse()
        ->and((float) $spk->project->fresh()->nominal)->toBe(178000000.0);
});

test('an invalid SPK is not published', function () {
    $spk = ProjectSpk::factory()->create(['scope' => '']);

    expect(fn () => app(SpkPublisher::class)->publish($spk, $this->user->id))->toThrow(ProposalValidationException::class)
        ->and($spk->fresh()->isPublished())->toBeFalse();
});
