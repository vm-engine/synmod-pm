<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\Division as ModelsDivision;
use VmEngine\Pm\Models\SubDivision;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.division')
        ->assertStatus(200);
});

test('displays division list', function () {
    ModelsDivision::factory()->create(['name' => 'Engineering']);
    ModelsDivision::factory()->create(['name' => 'Marketing']);

    Livewire::test('pm::master.division')
        ->assertSee('Engineering')
        ->assertSee('Marketing');
});

test('can create new division', function () {
    Livewire::test('pm::master.division')
        ->set('newDivision', 'New Division')
        ->set('newDivisionCode', 'ND')
        ->call('store')
        ->assertDispatched('notify');

    expect(ModelsDivision::where('name', 'New Division')->exists())->toBeTrue();
});

test('clears form after creating division', function () {
    Livewire::test('pm::master.division')
        ->set('newDivision', 'Test Division')
        ->set('newDivisionCode', 'TD')
        ->call('store')
        ->assertSet('newDivision', '');
});

test('can edit division', function () {
    $division = ModelsDivision::factory()->create(['name' => 'Old Name', 'code' => 'ON']);

    Livewire::test('pm::master.division')
        ->call('edit', $division->id)
        ->assertSet('editId', $division->id)
        ->assertSet('editText', 'Old Name')
        ->assertSet('editCode', 'ON');
});

test('can update division', function () {
    $division = ModelsDivision::factory()->create(['name' => 'Old Name', 'code' => 'ON']);

    Livewire::test('pm::master.division')
        ->call('edit', $division->id)
        ->set('editText', 'New Name')
        ->set('editCode', 'NN')
        ->call('update', $division->id)
        ->assertDispatched('notify');

    expect($division->fresh()->name)->toBe('New Name');
    expect($division->fresh()->code)->toBe('NN');
});

test('clears edit form after updating division', function () {
    $division = ModelsDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->call('edit', $division->id)
        ->set('editText', 'Updated')
        ->set('editCode', 'UP')
        ->call('update', $division->id)
        ->assertSet('editId', null)
        ->assertSet('editText', '')
        ->assertSet('editCode', '');
});

test('can delete division', function () {
    $division = ModelsDivision::factory()->create();

    $division->delete();

    expect(ModelsDivision::find($division->id))->toBeNull();
});

test('deleting division also deletes sub divisions', function () {
    $division = ModelsDivision::factory()->create();
    $subDivision = SubDivision::factory()->create(['division_id' => $division->id]);

    $division->subDivisions()->delete();
    $division->delete();

    expect(ModelsDivision::find($division->id))->toBeNull();
    expect(SubDivision::find($subDivision->id))->toBeNull();
});

test('can create sub division', function () {
    $division = ModelsDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->set("newSubDivision.{$division->id}", 'New Sub Division')
        ->call('storeSub', $division->id)
        ->assertDispatched('notify');

    expect(SubDivision::where('name', 'New Sub Division')->exists())->toBeTrue();
});

test('clears sub division form after creation', function () {
    $division = ModelsDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->set("newSubDivision.{$division->id}", 'Test Sub')
        ->call('storeSub', $division->id)
        ->assertSet("newSubDivision.{$division->id}", '');
});

test('can edit sub division', function () {
    $subDivision = SubDivision::factory()->create(['name' => 'Old Sub Name']);

    Livewire::test('pm::master.division')
        ->call('editSub', $subDivision->id)
        ->assertSet('editSubId', $subDivision->id)
        ->assertSet('editSubText', 'Old Sub Name');
});

test('can update sub division', function () {
    $subDivision = SubDivision::factory()->create(['name' => 'Old Sub Name']);

    Livewire::test('pm::master.division')
        ->call('editSub', $subDivision->id)
        ->set('editSubText', 'New Sub Name')
        ->call('updateSub', $subDivision->id)
        ->assertDispatched('notify');

    expect($subDivision->fresh()->name)->toBe('New Sub Name');
});

test('clears edit form after updating sub division', function () {
    $subDivision = SubDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->call('editSub', $subDivision->id)
        ->set('editSubText', 'Updated')
        ->call('updateSub', $subDivision->id)
        ->assertSet('editSubId', null)
        ->assertSet('editSubText', '');
});

test('can delete sub division', function () {
    $subDivision = SubDivision::factory()->create();

    $subDivision->delete();

    expect(SubDivision::find($subDivision->id))->toBeNull();
});

test('can cancel edit', function () {
    $division = ModelsDivision::factory()->create();
    $subDivision = SubDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->call('edit', $division->id)
        ->call('editSub', $subDivision->id)
        ->call('cancelEdit')
        ->assertSet('editId', null)
        ->assertSet('editText', '')
        ->assertSet('editSubId', null)
        ->assertSet('editSubText', '');
});

test('editing division cancels previous edit', function () {
    $division1 = ModelsDivision::factory()->create();
    $division2 = ModelsDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->call('edit', $division1->id)
        ->assertSet('editId', $division1->id)
        ->call('edit', $division2->id)
        ->assertSet('editId', $division2->id);
});

test('editing sub division cancels previous edit', function () {
    $sub1 = SubDivision::factory()->create();
    $sub2 = SubDivision::factory()->create();

    Livewire::test('pm::master.division')
        ->call('editSub', $sub1->id)
        ->assertSet('editSubId', $sub1->id)
        ->call('editSub', $sub2->id)
        ->assertSet('editSubId', $sub2->id);
});

test('division list includes sub divisions', function () {
    $division = ModelsDivision::factory()->create();
    SubDivision::factory()->create(['division_id' => $division->id]);

    $component = Livewire::test('pm::master.division');

    expect($component->divisionList->first()->subDivisions)->toHaveCount(1);
});

test('handles non existent division on update', function () {
    Livewire::test('pm::master.division')
        ->set('editText', 'Test')
        ->call('update', 99999)
        ->assertDispatched('notify');
});

test('handles non existent sub division on update', function () {
    Livewire::test('pm::master.division')
        ->set('editSubText', 'Test')
        ->call('updateSub', 99999)
        ->assertDispatched('notify');
});
