<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectProposal;

beforeEach(function () {
    Storage::fake('local');
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('application modules and sub items are saved, empty names dropped', function () {
    $proposal = ProjectProposal::factory()->create(['features' => []]);

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('addRow', 'features')
        ->set('features.0.name', 'Inventory')
        ->call('addChild', 'features', 0)
        ->set('features.0.children.0.name', 'Stock Opname')
        ->call('addChild', 'features', 0)
        ->call('addRow', 'features')
        ->call('save');

    $features = $proposal->fresh()->features;
    expect($features)->toHaveCount(1)
        ->and($features[0]['name'])->toBe('Inventory')
        ->and(array_column($features[0]['children'], 'name'))->toBe(['Stock Opname']);
});

test('website checklist edits and custom items are saved', function () {
    $proposal = ProjectProposal::factory()->website()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('features.0.checked', false)
        ->set('features.1.description', 'Halaman Kebijakan dan Berita.')
        ->call('addCustomFeature')
        ->set('features.8.name', 'Modul Download')
        ->call('save');

    $features = $proposal->fresh()->features;
    expect($features)->toHaveCount(9)
        ->and($features[0]['checked'])->toBeFalse()
        ->and($features[1]['description'])->toBe('Halaman Kebijakan dan Berita.')
        ->and($features[8])->toMatchArray(['name' => 'Modul Download', 'checked' => true, 'item_id' => null]);
});

test('menu tree and goal are saved for website proposals', function () {
    $proposal = ProjectProposal::factory()->website()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('scope.goal', 'Arsip tulisan.')
        ->call('addRow', 'scope.menu')
        ->set('scope.menu.0.name', 'Tentang')
        ->call('addChild', 'scope.menu', 0)
        ->set('scope.menu.0.children.0.name', 'Tentang Saya')
        ->call('save');

    $scope = $proposal->fresh()->scope;
    expect($scope['goal'])->toBe('Arsip tulisan.')
        ->and($scope['menu'][0]['children'][0]['name'])->toBe('Tentang Saya');
});

test('mockups upload to the local disk and can be removed', function () {
    $proposal = ProjectProposal::factory()->website()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('mockupUploads', [UploadedFile::fake()->image('home.png', 800, 1000)])
        ->set('scope.mockups.0.caption', 'Home')
        ->call('save');

    $mockups = $proposal->fresh()->scope['mockups'];
    expect($mockups)->toHaveCount(1)
        ->and($mockups[0]['caption'])->toBe('Home')
        ->and(Storage::disk('local')->exists($mockups[0]['path']))->toBeTrue();

    $component->call('removeMockup', 0)->call('save');
    expect($proposal->fresh()->scope['mockups'])->toBe([])
        ->and(Storage::disk('local')->exists($mockups[0]['path']))->toBeFalse();
});

test('non-image mockups are rejected', function () {
    $proposal = ProjectProposal::factory()->website()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('mockupUploads', [UploadedFile::fake()->create('evil.php', 10, 'text/x-php')])
        ->assertHasErrors('mockupUploads.0');
});

test('a tampered mockup path outside the proposal folder is never deleted or saved', function () {
    Storage::disk('local')->put('projects/99/documents/proposal/secret.pdf', 'keep me');
    $proposal = ProjectProposal::factory()->website()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->set('scope.mockups', [
            ['path' => 'projects/99/documents/proposal/secret.pdf', 'caption' => 'x'],
            ['path' => 'projects/'.$proposal->project_id.'/proposal/mockups/../../../99/documents/proposal/secret.pdf', 'caption' => 'y'],
        ])
        ->call('removeMockup', 0)
        ->call('removeMockup', 0)
        ->set('scope.mockups', [['path' => 'projects/99/documents/proposal/secret.pdf', 'caption' => 'z']])
        ->call('save');

    expect(Storage::disk('local')->exists('projects/99/documents/proposal/secret.pdf'))->toBeTrue()
        ->and($proposal->fresh()->scope['mockups'])->toBe([]);
});

test('an unknown proposal type is rejected', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::proposal.proposal-form', ['id' => $project->id])
        ->call('create', 9)
        ->assertStatus(400);
});
