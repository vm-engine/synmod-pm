<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use VmEngine\Pm\Models\ProjectProposal;
use VmEngine\Pm\Models\ProjectSph;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('opening the page creates the draft from the proposal', function () {
    $proposal = ProjectProposal::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $proposal->project_id])
        ->assertCount('items', 2)
        ->assertSet('showTimeplan', true);

    expect($proposal->project->fresh()->sph)->not->toBeNull();
});

test('save persists items with parsed prices and recomputes the total', function () {
    $sph = ProjectSph::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->call('addRow', 'items')
        ->set('items.2.name', 'Bimtek')
        ->set('items.2.price', 'Rp. 5.000.000')
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $sph->fresh();
    expect($fresh->items)->toHaveCount(3)
        ->and((float) $fresh->items[2]['price'])->toBe(5000000.0)
        ->and((float) $fresh->grand_total)->toBe(25000000.0);
});

test('unknown list names are rejected', function () {
    $sph = ProjectSph::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->call('removeRow', 'project', 0)
        ->assertStatus(400);
});

test('timeline rows can be edited through the shared trait', function () {
    $sph = ProjectSph::factory()->create();

    $component = Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])->call('indentTask', 1);

    expect($component->get('timeline.tasks.1.parent_id'))->toBe('t1');
});

test('import from proposal replaces items', function () {
    $proposal = ProjectProposal::factory()->create();
    $sph = ProjectSph::factory()->create(['project_id' => $proposal->project_id]);

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->call('importFromProposal')
        ->assertSet('items.0.name', $proposal->costing[0]['section']);
});

test('preview of an invalid SPH flags the tab and returns no url', function () {
    $sph = ProjectSph::factory()->create(['recipient_name' => '']);

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->call('preview', 'pdf')
        ->assertReturned(null)
        ->assertSet('tabErrors.general', [__('pm::sph.error_recipient')]);
});

test('publish stores a document', function () {
    $sph = ProjectSph::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])->call('publish');

    expect($sph->fresh()->isPublished())->toBeTrue();
});

test('attachments upload to the project folder and foreign paths are never deleted', function () {
    $sph = ProjectSph::factory()->create();
    Storage::disk('local')->put('secret.txt', 'keep');

    $component = Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->set('attachmentUploads', [UploadedFile::fake()->image('flow.png')]);

    $path = $component->get('attachments.0.path');
    expect($path)->toStartWith('projects/'.$sph->project_id.'/sph/attachments/');

    $component->set('attachments.0.path', '../secret.txt')->call('removeAttachment', 0);
    expect(Storage::disk('local')->exists('secret.txt'))->toBeTrue();
});

test('the sph id cannot be changed from the browser', function () {
    $sph = ProjectSph::factory()->create();
    $other = ProjectSph::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->set('sphId', $other->id);
})->throws(CannotUpdateLockedPropertyException::class);

test('the DP percentage is clamped to 0-100 on save', function () {
    $sph = ProjectSph::factory()->create();

    Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->set('dpPercentage', '100000')
        ->call('save')
        ->assertHasNoErrors();

    expect((float) $sph->fresh()->dp_percentage)->toBe(100.0);
});

test('a removed attachment file is deleted only when the removal is saved', function () {
    $sph = ProjectSph::factory()->create();

    $component = Livewire::test('pm::sph.sph-form', ['id' => $sph->project_id])
        ->set('attachmentUploads', [UploadedFile::fake()->image('flow.png')])
        ->call('save');
    $path = $component->get('attachments.0.path');

    $component->call('removeAttachment', 0);
    expect(Storage::disk('local')->exists($path))->toBeTrue();

    $component->call('save');
    expect(Storage::disk('local')->exists($path))->toBeFalse()
        ->and($sph->fresh()->attachments)->toBe([]);
});
