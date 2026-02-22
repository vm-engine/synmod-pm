<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\ProjectCategory as ModelsProjectCategory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.project-category')
        ->assertStatus(200);
});

test('loads category list', function () {
    ModelsProjectCategory::factory()->count(3)->create();

    $component = Livewire::test('pm::master.project-category');

    expect($component->categoryList)->toHaveCount(3);
});

test('can create new project category', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Web Development')
        ->set('newCode', 'WEB')
        ->set('newDescription', 'Web development projects')
        ->call('store')
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    expect(ModelsProjectCategory::where('code', 'WEB')->exists())->toBeTrue();

    $category = ModelsProjectCategory::where('code', 'WEB')->first();
    expect($category->name)->toBe('Web Development');
    expect($category->description)->toBe('Web development projects');
});

test('clears form after successful creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', 'TEST')
        ->set('newDescription', 'Test description')
        ->call('store')
        ->assertSet('newName', '')
        ->assertSet('newCode', '')
        ->assertSet('newDescription', '');
});

test('validates required name for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', '')
        ->set('newCode', 'TEST')
        ->call('store')
        ->assertHasErrors(['newName' => 'required']);
});

test('validates required code for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', '')
        ->call('store')
        ->assertHasErrors(['newCode' => 'required']);
});

test('validates unique code for creation', function () {
    ModelsProjectCategory::factory()->create(['code' => 'EXISTING']);

    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', 'EXISTING')
        ->call('store')
        ->assertHasErrors(['newCode' => 'unique']);
});

test('validates name max length for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', str_repeat('a', 256))
        ->set('newCode', 'TEST')
        ->call('store')
        ->assertHasErrors(['newName' => 'max']);
});

test('validates code max length for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', str_repeat('A', 51))
        ->call('store')
        ->assertHasErrors(['newCode' => 'max']);
});

test('validates description max length for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', 'TEST')
        ->set('newDescription', str_repeat('a', 501))
        ->call('store')
        ->assertHasErrors(['newDescription' => 'max']);
});

test('description is optional for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', 'TEST')
        ->set('newDescription', '')
        ->call('store')
        ->assertHasNoErrors();

    expect(ModelsProjectCategory::where('code', 'TEST')->exists())->toBeTrue();
});

test('can load category for editing', function () {
    $category = ModelsProjectCategory::factory()->create([
        'name' => 'Web Development',
        'code' => 'WEB',
        'description' => 'Web projects',
    ]);

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->assertSet('editId', $category->id)
        ->assertSet('editName', 'Web Development')
        ->assertSet('editCode', 'WEB')
        ->assertSet('editDescription', 'Web projects');
});

test('can update existing category', function () {
    $category = ModelsProjectCategory::factory()->create([
        'name' => 'Old Name',
        'code' => 'OLD',
        'description' => 'Old description',
    ]);

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->set('editName', 'Updated Name')
        ->set('editCode', 'UPDATED')
        ->set('editDescription', 'Updated description')
        ->call('update', $category->id)
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    expect($category->fresh()->name)->toBe('Updated Name');
    expect($category->fresh()->code)->toBe('UPDATED');
    expect($category->fresh()->description)->toBe('Updated description');
});

test('clears edit form after successful update', function () {
    $category = ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->set('editName', 'Updated Name')
        ->call('update', $category->id)
        ->assertSet('editId', null)
        ->assertSet('editName', '')
        ->assertSet('editCode', '')
        ->assertSet('editDescription', '');
});

test('validates required name for update', function () {
    $category = ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->set('editName', '')
        ->call('update', $category->id)
        ->assertHasErrors(['editName' => 'required']);
});

test('validates required code for update', function () {
    $category = ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->set('editCode', '')
        ->call('update', $category->id)
        ->assertHasErrors(['editCode' => 'required']);
});

test('validates unique code for update excluding current category', function () {
    $category1 = ModelsProjectCategory::factory()->create(['code' => 'CAT1']);
    $category2 = ModelsProjectCategory::factory()->create(['code' => 'CAT2']);

    // Should be able to keep same code
    Livewire::test('pm::master.project-category')
        ->call('edit', $category1->id)
        ->set('editName', 'Updated Name')
        ->set('editCode', 'CAT1')
        ->call('update', $category1->id)
        ->assertHasNoErrors();

    // Should not be able to use another category's code
    Livewire::test('pm::master.project-category')
        ->call('edit', $category1->id)
        ->set('editCode', 'CAT2')
        ->call('update', $category1->id)
        ->assertHasErrors(['editCode' => 'unique']);
});

test('can cancel edit', function () {
    $category = ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->assertSet('editId', $category->id)
        ->call('cancelEdit')
        ->assertSet('editId', null)
        ->assertSet('editName', '')
        ->assertSet('editCode', '')
        ->assertSet('editDescription', '');
});

test('can delete category', function () {
    $category = ModelsProjectCategory::factory()->create();
    $token = $category->delete_token;

    Livewire::test('pm::master.project-category')
        ->call('delete', $token)
        ->assertDispatched('synapse-confirmed')
        ->assertDispatched('notify');

    expect(ModelsProjectCategory::find($category->id))->toBeNull();
});

test('handles deletion with invalid token', function () {
    ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('delete', 'invalid-token')
        ->assertDispatched('notify');
});

// Note: Cannot test non-existent category deletion without a valid token from an actual model instance

test('handles update of non-existent category', function () {
    Livewire::test('pm::master.project-category')
        ->set('editName', 'Test')
        ->set('editCode', 'TEST')
        ->call('update', 99999)
        ->assertDispatched('notify');
});

test('can load document indexes', function () {
    $category = ModelsProjectCategory::factory()->create(['code' => 'TEST']);

    Livewire::test('pm::master.project-category')
        ->call('loadIndexes', $category->id)
        ->assertSet('indexingId', $category->id)
        ->assertDispatched('open-modal-document-index');
});

test('document indexes include all document types', function () {
    $category = ModelsProjectCategory::factory()->create(['code' => 'TEST']);

    $component = Livewire::test('pm::master.project-category')
        ->call('loadIndexes', $category->id);

    expect($component->documentIndexes)->not->toBeEmpty();
});

test('can update document indexes', function () {
    $category = ModelsProjectCategory::factory()->create(['code' => 'TEST']);

    Livewire::test('pm::master.project-category')
        ->call('loadIndexes', $category->id)
        ->call('updateIndexes')
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-document-index');
});

test('can reset document indexes', function () {
    $category = ModelsProjectCategory::factory()->create(['code' => 'TEST']);

    Livewire::test('pm::master.project-category')
        ->call('loadIndexes', $category->id)
        ->assertSet('indexingId', $category->id)
        ->dispatch('close-modal-document-index')
        ->assertSet('indexingId', null)
        ->assertSet('documentIndexes', []);
});

test('handles loading indexes for non-existent category', function () {
    Livewire::test('pm::master.project-category')
        ->call('loadIndexes', 99999)
        ->assertDispatched('notify');
});

test('handles updating indexes for non-existent category', function () {
    Livewire::test('pm::master.project-category')
        ->set('indexingId', 99999)
        ->call('updateIndexes')
        ->assertDispatched('notify');
});

test('sets active menu', function () {
    Livewire::test('pm::master.project-category');

    expect(synav()->getActiveMenu())->toBe('pm.master.project_category');
});

test('edit clears previous edit data', function () {
    $category1 = ModelsProjectCategory::factory()->create(['name' => 'Category 1']);
    $category2 = ModelsProjectCategory::factory()->create(['name' => 'Category 2']);

    Livewire::test('pm::master.project-category')
        ->call('edit', $category1->id)
        ->assertSet('editName', 'Category 1')
        ->call('edit', $category2->id)
        ->assertSet('editName', 'Category 2');
});

test('shows success message for creation', function () {
    Livewire::test('pm::master.project-category')
        ->set('newName', 'Test Category')
        ->set('newCode', 'TEST')
        ->call('store')
        ->assertDispatched('notify');
});

test('shows success message for update', function () {
    $category = ModelsProjectCategory::factory()->create();

    Livewire::test('pm::master.project-category')
        ->call('edit', $category->id)
        ->set('editName', 'Updated Name')
        ->call('update', $category->id)
        ->assertDispatched('notify');
});

test('shows success message for deletion', function () {
    $category = ModelsProjectCategory::factory()->create();
    $token = $category->delete_token;

    Livewire::test('pm::master.project-category')
        ->call('delete', $token)
        ->assertDispatched('notify');
});
