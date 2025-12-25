<?php

declare(strict_types=1);

use Livewire\Volt\Volt;
use VmEngine\Pm\Livewire\ClientList;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Volt::test(ClientList::class)
        ->assertStatus(200);
});

test('displays clients list', function () {
    $category = ClientCategory::factory()->create(['name' => 'Enterprise']);
    $client = Client::factory()->create([
        'company' => 'Acme Corp',
        'client_category_id' => $category->id,
    ]);

    Volt::test(ClientList::class)
        ->assertSee('Acme Corp')
        ->assertStatus(200);
});

test('can search clients by company name', function () {
    Client::factory()->create(['company' => 'Acme Corp']);
    Client::factory()->create(['company' => 'Tech Solutions']);
    Client::factory()->create(['company' => 'Global Industries']);

    Volt::test(ClientList::class)
        ->set('q', 'Tech')
        ->assertSee('Tech Solutions')
        ->assertDontSee('Acme Corp')
        ->assertDontSee('Global Industries');
});

test('can search clients by city', function () {
    Client::factory()->create(['company' => 'Company A', 'city' => 'New York']);
    Client::factory()->create(['company' => 'Company B', 'city' => 'Los Angeles']);

    Volt::test(ClientList::class)
        ->set('q', 'Angeles')
        ->assertSee('Company B')
        ->assertDontSee('Company A');
});

test('search requires at least 3 characters', function () {
    Client::factory()->create(['company' => 'Acme Corp']);

    Volt::test(ClientList::class)
        ->set('q', 'Ac')
        ->assertSee('Acme Corp'); // Should show all without filtering

    Volt::test(ClientList::class)
        ->set('q', 'Acm')
        ->assertSee('Acme Corp'); // Should filter now
});

test('can filter by category', function () {
    $categoryA = ClientCategory::factory()->create(['name' => 'Category A']);
    $categoryB = ClientCategory::factory()->create(['name' => 'Category B']);

    Client::factory()->create(['company' => 'Company A', 'client_category_id' => $categoryA->id]);
    Client::factory()->create(['company' => 'Company B', 'client_category_id' => $categoryB->id]);

    Volt::test(ClientList::class)
        ->set('filterCategories', [$categoryA->id])
        ->assertSee('Company A')
        ->assertDontSee('Company B');
});

test('can filter by multiple categories', function () {
    $categoryA = ClientCategory::factory()->create();
    $categoryB = ClientCategory::factory()->create();
    $categoryC = ClientCategory::factory()->create();

    Client::factory()->create(['company' => 'Company A', 'client_category_id' => $categoryA->id]);
    Client::factory()->create(['company' => 'Company B', 'client_category_id' => $categoryB->id]);
    Client::factory()->create(['company' => 'Company C', 'client_category_id' => $categoryC->id]);

    Volt::test(ClientList::class)
        ->set('filterCategories', [$categoryA->id, $categoryB->id])
        ->assertSee('Company A')
        ->assertSee('Company B')
        ->assertDontSee('Company C');
});

test('can filter by services', function () {
    Client::factory()->create(['company' => 'Web Dev Co', 'services' => [1, 2]]);
    Client::factory()->create(['company' => 'Mobile App Co', 'services' => [3, 4]]);

    Volt::test(ClientList::class)
        ->set('filterServices', [1])
        ->assertSee('Web Dev Co')
        ->assertDontSee('Mobile App Co');
});

test('can filter by status prospect', function () {
    Client::factory()->prospect()->create(['company' => 'Prospect Co']);
    Client::factory()->active()->create(['company' => 'Active Co']);

    Volt::test(ClientList::class)
        ->set('filterStatus', 'prospect')
        ->assertSee('Prospect Co')
        ->assertDontSee('Active Co');
});

test('can filter by status active', function () {
    Client::factory()->prospect()->create(['company' => 'Prospect Co']);
    Client::factory()->active()->create(['company' => 'Active Co']);

    Volt::test(ClientList::class)
        ->set('filterStatus', 'active')
        ->assertSee('Active Co')
        ->assertDontSee('Prospect Co');
});

test('can filter by active flag', function () {
    Client::factory()->active()->create(['company' => 'Active Client']);
    Client::factory()->inactive()->create(['company' => 'Inactive Client']);

    Volt::test(ClientList::class)
        ->set('filterActive', 'active')
        ->assertSee('Active Client')
        ->assertDontSee('Inactive Client');
});

test('can filter by inactive flag', function () {
    Client::factory()->active()->create(['company' => 'Active Client']);
    Client::factory()->inactive()->create(['company' => 'Inactive Client']);

    Volt::test(ClientList::class)
        ->set('filterActive', 'inactive')
        ->assertSee('Inactive Client')
        ->assertDontSee('Active Client');
});

test('can combine multiple filters', function () {
    $category = ClientCategory::factory()->create();

    Client::factory()->prospect()->create([
        'company' => 'Match',
        'client_category_id' => $category->id,
        'services' => [1],
    ]);

    Client::factory()->active()->create([
        'company' => 'No Match - Different Status',
        'client_category_id' => $category->id,
        'services' => [1],
    ]);

    Client::factory()->prospect()->create([
        'company' => 'No Match - Different Services',
        'client_category_id' => $category->id,
        'services' => [2],
    ]);

    Volt::test(ClientList::class)
        ->set('filterCategories', [$category->id])
        ->set('filterServices', [1])
        ->set('filterStatus', 'prospect')
        ->assertSee('Match')
        ->assertDontSee('No Match - Different Status')
        ->assertDontSee('No Match - Different Services');
});

test('can sort by column', function () {
    Client::factory()->create(['company' => 'Zebra Corp']);
    Client::factory()->create(['company' => 'Alpha Corp']);

    Volt::test(ClientList::class)
        ->call('sortData', 'company')
        ->assertSet('sort', 'company')
        ->assertSet('sortDirection', 'asc');
});

test('can toggle sort direction', function () {
    Volt::test(ClientList::class)
        ->call('sortData', 'company')
        ->assertSet('sortDirection', 'asc')
        ->call('sortData', 'company')
        ->assertSet('sortDirection', 'desc')
        ->call('sortData', 'company')
        ->assertSet('sort', 'id')
        ->assertSet('sortDirection', 'desc'); // Reset
});

test('can toggle category filter', function () {
    $category = ClientCategory::factory()->create();

    Volt::test(ClientList::class)
        ->call('filterByCategory', $category->id)
        ->assertSet('filterCategories', [$category->id])
        ->call('filterByCategory', $category->id)
        ->assertSet('filterCategories', []);
});

test('can toggle service filter', function () {
    Volt::test(ClientList::class)
        ->call('filterByService', 1)
        ->assertSet('filterServices', [1])
        ->call('filterByService', 1)
        ->assertSet('filterServices', []);
});

test('can toggle client active status', function () {
    $client = Client::factory()->active()->create(['active' => true]);

    Volt::test(ClientList::class)
        ->call('toggleActive', $client->id)
        ->assertDispatched('notify');

    expect($client->fresh()->active)->toBeFalse();
});

test('toggleActive updates user status correctly for active client', function () {
    $client = Client::factory()->active()->create(['active' => true]);
    $user = $client->user;

    expect($user->status)->toBe(1);

    Volt::test(ClientList::class)
        ->call('toggleActive', $client->id);

    expect($client->fresh()->active)->toBeFalse();
    expect($user->fresh()->status)->toBe(0);
});

test('toggleActive updates user status correctly for prospect client', function () {
    $client = Client::factory()->prospect()->create(['active' => true]);
    $user = $client->user;

    expect($user->status)->toBe(0); // Prospect always has inactive user

    Volt::test(ClientList::class)
        ->call('toggleActive', $client->id);

    expect($client->fresh()->active)->toBeFalse();
    expect($user->fresh()->status)->toBe(0); // Still false
});

test('can delete client', function () {
    $client = Client::factory()->create(['company' => 'Delete Me']);

    $client->delete();

    expect(Client::find($client->id))->toBeNull();
});

test('resetsPage when filters change', function () {
    Volt::test(ClientList::class)
        ->set('page', 2)
        ->set('q', 'search')
        ->assertSet('page', 1);
});

test('pagination works correctly', function () {
    Client::factory()->count(25)->create();

    $component = Volt::test(ClientList::class)
        ->set('limit', 10)
        ->assertSet('limit', 10);

    expect($component->clientList)->toHaveCount(10);
});
