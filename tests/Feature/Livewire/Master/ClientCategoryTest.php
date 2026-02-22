<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\ClientCategory as ModelsClientCategory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.client-category')
        ->assertStatus(200);
});

test('displays category list', function () {
    ModelsClientCategory::factory()->create(['name' => 'Enterprise']);
    ModelsClientCategory::factory()->create(['name' => 'SMB']);

    Livewire::test('pm::master.client-category')
        ->assertSee('Enterprise')
        ->assertSee('SMB');
});

test('displays categories ordered by name', function () {
    ModelsClientCategory::factory()->create(['name' => 'Zebra']);
    ModelsClientCategory::factory()->create(['name' => 'Alpha']);

    $component = Livewire::test('pm::master.client-category');

    $categories = $component->categoryList;
    expect($categories->first()->name)->toBe('Alpha');
    expect($categories->last()->name)->toBe('Zebra');
});

test('can create new category with name only', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', 'New Category')
        ->call('store')
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    expect(ModelsClientCategory::where('name', 'New Category')->exists())->toBeTrue();
});

test('can create new category with name and description', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', 'Enterprise Clients')
        ->set('newDescription', 'Large enterprise customers')
        ->call('store')
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    $category = ModelsClientCategory::where('name', 'Enterprise Clients')->first();
    expect($category)->not->toBeNull();
    expect($category->description)->toBe('Large enterprise customers');
});

test('validates name is required', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', '')
        ->call('store')
        ->assertHasErrors(['newName']);
});

test('validates name max length', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', str_repeat('a', 256))
        ->call('store')
        ->assertHasErrors(['newName']);
});

test('validates description max length', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', 'Test')
        ->set('newDescription', str_repeat('a', 501))
        ->call('store')
        ->assertHasErrors(['newDescription']);
});

test('clears form after creating category', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', 'Test Category')
        ->set('newDescription', 'Test Description')
        ->call('store')
        ->assertSet('newName', '')
        ->assertSet('newDescription', '');
});

test('can edit category', function () {
    $category = ModelsClientCategory::factory()->create([
        'name' => 'Old Name',
        'description' => 'Old Description',
    ]);

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->assertSet('editId', $category->id)
        ->assertSet('editName', 'Old Name')
        ->assertSet('editDescription', 'Old Description');
});

test('can update category', function () {
    $category = ModelsClientCategory::factory()->create(['name' => 'Old Name']);

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editName', 'New Name')
        ->set('editDescription', 'New Description')
        ->call('update', $category->id)
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    expect($category->fresh()->name)->toBe('New Name');
    expect($category->fresh()->description)->toBe('New Description');
});

test('validates edit name is required', function () {
    $category = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editName', '')
        ->call('update', $category->id)
        ->assertHasErrors(['editName']);
});

test('validates edit name max length', function () {
    $category = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editName', str_repeat('a', 256))
        ->call('update', $category->id)
        ->assertHasErrors(['editName']);
});

test('validates edit description max length', function () {
    $category = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editName', 'Test')
        ->set('editDescription', str_repeat('a', 501))
        ->call('update', $category->id)
        ->assertHasErrors(['editDescription']);
});

test('clears edit form after updating category', function () {
    $category = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editName', 'Updated')
        ->call('update', $category->id)
        ->assertSet('editId', null)
        ->assertSet('editName', '')
        ->assertSet('editDescription', '');
});

test('can cancel edit', function () {
    $category = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->call('cancelEdit')
        ->assertSet('editId', null)
        ->assertSet('editName', '')
        ->assertSet('editDescription', '');
});

test('editing category cancels previous edit', function () {
    $category1 = ModelsClientCategory::factory()->create();
    $category2 = ModelsClientCategory::factory()->create();

    Livewire::test('pm::master.client-category')
        ->call('edit', $category1->id)
        ->assertSet('editId', $category1->id)
        ->call('edit', $category2->id)
        ->assertSet('editId', $category2->id);
});

test('can delete category', function () {
    $category = ModelsClientCategory::factory()->create();

    $category->delete();

    expect(ModelsClientCategory::find($category->id))->toBeNull();
});

test('handles non existent category on edit', function () {
    expect(fn () => Livewire::test('pm::master.client-category')
        ->call('edit', 99999)
    )->toThrow(Exception::class);
});

test('handles non existent category on update', function () {
    Livewire::test('pm::master.client-category')
        ->set('editName', 'Test')
        ->call('update', 99999)
        ->assertDispatched('notify');
});

test('can create category with null description', function () {
    Livewire::test('pm::master.client-category')
        ->set('newName', 'Test Category')
        ->set('newDescription', '')
        ->call('store')
        ->assertHasNoErrors();

    $category = ModelsClientCategory::where('name', 'Test Category')->first();
    expect($category->description)->toBeNull();
});

test('can update category to have null description', function () {
    $category = ModelsClientCategory::factory()->create(['description' => 'Has description']);

    Livewire::test('pm::master.client-category')
        ->call('edit', $category->id)
        ->set('editDescription', '')
        ->call('update', $category->id)
        ->assertHasNoErrors();

    expect($category->fresh()->description)->toBeNull();
});
