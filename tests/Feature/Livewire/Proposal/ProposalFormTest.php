<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Services\Proposal\Exceptions\ProposalValidationException;
use VmEngine\Pm\Services\Proposal\ProposalPublisher;

use function Pest\Laravel\mock;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('a project without a proposal shows the create screen and can create one', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $project->id])
        ->assertSee(__('pm::proposal.create_heading'))
        ->call('create', ProposalType::Website->value)
        ->assertSet('type', ProposalType::Website->value)
        ->assertCount('features', 8);

    expect($project->fresh()->proposal)->not->toBeNull();
});

test('saving persists costing lists and recomputes the total', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('costing.0.features', "Line A\n\nLine B ")
        ->set('costing.0.price', 1000)
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $proposal->fresh();
    expect($fresh->costing[0]['features'])->toBe(['Line A', 'Line B'])
        ->and((float) $fresh->grand_total)->toBe(160001000.0);
});

test('normalizes formatted currency input', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('costing.0.price', '15.000.000')
        ->set('costing.1.price', 'Rp. 5.000.000,00')
        ->call('save');

    expect((float) $proposal->fresh()->grand_total)->toBe(20000000.0);
});

test('rows can be added, moved and removed', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('addRow', 'deliverables')
        ->assertCount('deliverables', 6)
        ->call('moveRow', 'deliverables', 5, -1)
        ->call('removeRow', 'deliverables', 4)
        ->assertCount('deliverables', 5);
});

test('unknown lists are rejected', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('addRow', 'project')
        ->assertStatus(400);
});

test('preview with invalid data flags tabs and returns no url', function () {
    $proposal = ProjectProposal::factory()->create(['features' => []]);

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('preview', 'pdf')
        ->assertSet('tabErrors.features', [__('pm::proposal.error_modules_empty')])
        ->assertReturned(null);
});

test('preview with valid data returns the preview url', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('preview', 'pdf')
        ->assertReturned(backend_route('pm.proposal.preview', ['id' => $proposal->project_id, 'format' => 'pdf']));
});

test('publish delegates to the publisher', function () {
    $proposal = ProjectProposal::factory()->create();
    mock(ProposalPublisher::class)->shouldReceive('publish')->once();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('publish')
        ->assertHasNoErrors();
});

test('publish shows validation errors from the publisher', function () {
    $proposal = ProjectProposal::factory()->create();
    mock(ProposalPublisher::class)->shouldReceive('publish')->andThrow(new ProposalValidationException(['costing' => ['bad']]));

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('publish')
        ->assertSet('tabErrors.costing', ['bad']);
});

test('changing type replaces the features', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('changeType', ProposalType::Website->value)
        ->assertSet('type', ProposalType::Website->value)
        ->assertCount('features', 8);
});

test('the publish confirm message is escaped for the inline JS string', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->assertSeeHtml("message: '".e(addslashes(__('pm::proposal.publish_confirm')))."'");
});
