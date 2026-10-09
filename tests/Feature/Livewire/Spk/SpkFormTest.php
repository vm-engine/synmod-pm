<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\Livewire;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Models\ProjectSpk;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('opening the page creates the draft from the SPH', function () {
    $sph = ProjectSph::factory()->create();

    Livewire::test('pm::spk.spk-form', ['id' => $sph->project_id])
        ->assertSet('contractValue', 20000000.0)
        ->assertCount('installments', 2);

    expect($sph->project->fresh()->spk)->not->toBeNull();
});

test('save persists installments with parsed amounts', function () {
    $spk = ProjectSpk::factory()->create();

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->set('installments.1.value', 'Rp. 13.500.000')
        ->set('installments.1.count', '10')
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $spk->fresh();
    expect((float) $fresh->installments[1]['value'])->toBe(13500000.0)
        ->and($fresh->installments[1]['count'])->toBe(10);
});

test('extra pasal rows can be added and shift the tab pasal numbers', function () {
    $spk = ProjectSpk::factory()->create();

    $component = Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->call('addRow', 'extraPasal')
        ->set('extraPasal.0.title', 'Garansi')
        ->set('extraPasal.0.body', 'Tiga bulan.')
        ->call('save')
        ->assertHasNoErrors();

    expect($spk->fresh()->extra_pasal[0]['title'])->toBe('Garansi')
        ->and($component->instance()->tabs['extra'])->toContain('10'); // server + korespondensi on: extra is Pasal 10
});

test('unknown list names are rejected', function () {
    $spk = ProjectSpk::factory()->create();

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->call('removeRow', 'project', 0)
        ->assertStatus(400);
});

test('import from SPH replaces value and installments', function () {
    $sph = ProjectSph::factory()->create();
    $spk = ProjectSpk::factory()->create(['project_id' => $sph->project_id]);

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->call('importFromSph')
        ->assertSet('contractValue', 20000000.0)
        ->assertSet('installments.0.type', 'percent');
});

test('preview of an invalid SPK flags the tab and returns no url', function () {
    $spk = ProjectSpk::factory()->create(['scope' => '']);

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->call('preview', 'pdf')
        ->assertReturned(null)
        ->assertSet('tabErrors.scope', [__('pm::spk.error_scope')]);
});

test('publish stores a document', function () {
    $spk = ProjectSpk::factory()->create();

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])->call('publish');

    expect($spk->fresh()->isPublished())->toBeTrue();
});

test('attachments upload to the project folder and foreign paths are dropped', function () {
    $spk = ProjectSpk::factory()->create();
    Storage::disk('local')->put('secret.txt', 'keep');

    $component = Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])
        ->set('attachmentUploads', [UploadedFile::fake()->image('org.png')]);

    expect($component->get('attachments.0.path'))->toStartWith('projects/'.$spk->project_id.'/spk/attachments/');

    $component->set('attachments.0.path', '../secret.txt')->call('save');
    expect(Storage::disk('local')->exists('secret.txt'))->toBeTrue()
        ->and($spk->fresh()->attachments)->toBe([]);
});

test('the spk id cannot be changed from the browser', function () {
    $spk = ProjectSpk::factory()->create();
    $other = ProjectSpk::factory()->create();

    Livewire::test('pm::spk.spk-form', ['id' => $spk->project_id])->set('spkId', $other->id);
})->throws(CannotUpdateLockedPropertyException::class);
