<?php

declare(strict_types=1);

use Illuminate\Support\Js;
use Livewire\Livewire;
use VmEngine\Pm\Enums\ProposalType;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProposalFeatureItem;
use VmEngine\Pm\Models\ProposalTypeDefault;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

test('holidays can be added and deleted, dates are unique', function () {
    Livewire::test('pm::master.holiday')
        ->set('newDate', '2026-03-20')
        ->set('newName', 'Idul Fitri')
        ->call('store')
        ->assertHasNoErrors();
    expect(Holiday::dates())->toBe(['2026-03-20']);

    Livewire::test('pm::master.holiday')
        ->set('newDate', '2026-03-20')
        ->set('newName', 'Duplicate')
        ->call('store')
        ->assertHasErrors('newDate');

    $holiday = Holiday::first();
    Livewire::test('pm::master.holiday')->call('delete', $holiday->delete_token);
    expect(Holiday::count())->toBe(0);
});

test('proposal features can be added, toggled and re-sorted', function () {
    Livewire::test('pm::master.proposal-feature')
        ->set('newName', 'Modul Download')
        ->set('newDescription', 'Unduhan dokumen.')
        ->call('store')
        ->assertHasNoErrors();

    $item = ProposalFeatureItem::query()->where('name', 'Modul Download')->firstOrFail();
    expect($item->sort)->toBe(90);

    Livewire::test('pm::master.proposal-feature')
        ->call('toggle', $item->id, 'active')
        ->call('updateSort', $item->id, 5);

    expect($item->fresh()->active)->toBeFalse()->and($item->fresh()->sort)->toBe(5);
});

test('toggle rejects unknown fields', function () {
    $item = ProposalFeatureItem::first();

    Livewire::test('pm::master.proposal-feature')->call('toggle', $item->id, 'name')->assertStatus(400);
});

test('type defaults can be edited', function () {
    Livewire::test('pm::master.proposal-default')
        ->call('selectType', ProposalType::Website->value)
        ->set('costing.0.price', '20.000.000')
        ->call('addRow', 'timeline.tasks')
        ->call('save')
        ->assertHasNoErrors();

    $row = ProposalTypeDefault::query()->where('type', ProposalType::Website->value)->firstOrFail();
    expect((float) $row->costing[0]['price'])->toBe(20000000.0)
        ->and($row->timeline_tasks)->toHaveCount(12);
});

test('names in confirm dialogs are encoded as JS strings', function () {
    Holiday::create(['date' => '2026-12-25', 'name' => "O'Brien's Day"]);
    ProposalFeatureItem::query()->first()->update(['name' => "Modul 'Quote'"]);

    Livewire::test('pm::master.holiday')
        ->assertSeeHtml('message: '.e(Js::from("O'Brien's Day")))
        ->assertDontSeeHtml("message: 'O&#039;Brien");
    Livewire::test('pm::master.proposal-feature')
        ->assertSeeHtml('message: '.e(Js::from("Modul 'Quote'")));
});
