<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProposalStatus;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Proposal\PdfProposalRenderer;
use VmEngine\Pm\Services\Proposal\ProposalPublisher;

use function Pest\Laravel\mock;

beforeEach(function () {
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    dbconf(['pm.documents.PROPOSAL.number_format' => '{INDEX}/{PROJECT_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('first publish assigns a number and stores version 1 with both files', function () {
    $proposal = ProjectProposal::factory()->create();

    $document = app(ProposalPublisher::class)->publish($proposal, $this->user->id);
    $proposal->refresh();

    expect($proposal->number)->toStartWith('001/')
        ->and($proposal->status)->toBe(ProposalStatus::Published)
        ->and($proposal->published_version)->toBe(1)
        ->and($proposal->has_unpublished_changes)->toBeFalse()
        ->and($proposal->published_snapshot['title'])->toBe($proposal->title)
        ->and($document->type)->toBe(Documents::PROPOSAL)
        ->and($document->version)->toBe(1)
        ->and($document->number)->toBe($proposal->number)
        ->and(Storage::disk('local')->exists($document->file_path))->toBeTrue()
        ->and(Storage::disk('local')->exists($document->file_info['pptx']))->toBeTrue()
        ->and((float) $proposal->project->fresh()->nominal)->toBe(220000000.0);
});

test('republishing keeps the number and adds version 2', function () {
    $proposal = ProjectProposal::factory()->create();
    $publisher = app(ProposalPublisher::class);
    $publisher->publish($proposal, $this->user->id);
    $number = $proposal->fresh()->number;

    $proposal->fresh()->update(['title' => 'REVISED']);
    $second = $publisher->publish($proposal->fresh(), $this->user->id);

    expect($proposal->fresh()->number)->toBe($number)
        ->and($second->version)->toBe(2)
        ->and(ProjectDocument::query()->where('project_id', $proposal->project_id)->count())->toBe(2);
});

test('an invalid proposal stores nothing', function () {
    $proposal = ProjectProposal::factory()->create(['costing' => []]);

    expect(fn () => app(ProposalPublisher::class)->publish($proposal, $this->user->id))
        ->toThrow(ProposalValidationException::class);
    expect(ProjectDocument::count())->toBe(0)
        ->and($proposal->fresh()->number)->toBeNull()
        ->and(Storage::disk('local')->allFiles())->toBe([]);
});

test('a rendering failure leaves no document, file or number behind', function () {
    mock(PdfProposalRenderer::class)->shouldReceive('render')->andThrow(new RuntimeException('boom'));
    $proposal = ProjectProposal::factory()->create();

    expect(fn () => app(ProposalPublisher::class)->publish($proposal, $this->user->id))->toThrow(RuntimeException::class);
    expect(ProjectDocument::count())->toBe(0)
        ->and($proposal->fresh()->number)->toBeNull()
        ->and($proposal->fresh()->status)->toBe(ProposalStatus::Draft)
        ->and(Storage::disk('local')->allFiles())->toBe([]);
});
