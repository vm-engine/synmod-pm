<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\ProposalPublisher;
use VmEngine\SynAuth\Models\Role;

use function Pest\Laravel\get;

beforeEach(function () {
    Storage::fake('local');
    $this->withoutMiddleware();
    $UserModel = config('auth.providers.users.model');
    $this->user = $UserModel::factory()->create();
    // Dev role passes ProjectDocumentService::canAccessDocument() (same setup as DocumentControllerTest)
    $this->user->roles()->attach(Role::firstOrCreate(['slug' => 'dev'], ['name' => 'Developer', 'level' => 0]));
    $this->actingAs($this->user);
    dbconf(['pm.documents.PROPOSAL.number_format' => '{INDEX}/{PROJECT_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('preview streams a draft pdf without storing a document', function () {
    $proposal = ProjectProposal::factory()->create();

    get(route('backend.pm.proposal.preview', ['id' => $proposal->project_id, 'format' => 'pdf']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('cache-control', 'no-store, private');

    expect($proposal->project->documents()->count())->toBe(0);
});

test('preview downloads a pptx', function () {
    $proposal = ProjectProposal::factory()->create();

    get(route('backend.pm.proposal.preview', ['id' => $proposal->project_id, 'format' => 'pptx']))
        ->assertOk()
        ->assertDownload();
});

test('preview of an invalid proposal is rejected', function () {
    $proposal = ProjectProposal::factory()->create(['costing' => []]);

    get(route('backend.pm.proposal.preview', ['id' => $proposal->project_id, 'format' => 'pdf']))->assertUnprocessable();
});

test('preview of a project without a proposal is not found', function () {
    $proposal = ProjectProposal::factory()->create();
    $proposal->delete();

    get(route('backend.pm.proposal.preview', ['id' => $proposal->project_id, 'format' => 'pdf']))->assertNotFound();
});

test('the pptx of a published version can be downloaded', function () {
    $proposal = ProjectProposal::factory()->create();
    $document = app(ProposalPublisher::class)->publish($proposal, $this->user->id);

    get(route('backend.pm.document.download_pptx', ['id' => $document->id]))
        ->assertOk()
        ->assertDownload($document->file_info['pptx_name']);
});
