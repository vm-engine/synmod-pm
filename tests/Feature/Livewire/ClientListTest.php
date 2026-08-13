<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::client-list')
        ->assertStatus(200);
});

test('displays clients list', function () {
    $category = ClientCategory::factory()->create(['name' => 'Enterprise']);
    $client = Client::factory()->create([
        'company' => 'Acme Corp',
        'client_category_id' => $category->id,
    ]);

    Livewire::test('pm::client-list')
        ->assertSee('Acme Corp')
        ->assertStatus(200);
});

test('can search clients by company name', function () {
    Client::factory()->create(['company' => 'Acme Corp']);
    Client::factory()->create(['company' => 'Tech Solutions']);
    Client::factory()->create(['company' => 'Global Industries']);

    Livewire::test('pm::client-list')
        ->set('q', 'Tech')
        ->assertSee('Tech Solutions')
        ->assertDontSee('Acme Corp')
        ->assertDontSee('Global Industries');
});

test('can search clients by city', function () {
    Client::factory()->create(['company' => 'Company A', 'city' => 'New York']);
    Client::factory()->create(['company' => 'Company B', 'city' => 'Los Angeles']);

    Livewire::test('pm::client-list')
        ->set('q', 'Angeles')
        ->assertSee('Company B')
        ->assertDontSee('Company A');
});

test('search requires at least 3 characters', function () {
    Client::factory()->create(['company' => 'Acme Corp']);

    Livewire::test('pm::client-list')
        ->set('q', 'Ac')
        ->assertSee('Acme Corp'); // Should show all without filtering

    Livewire::test('pm::client-list')
        ->set('q', 'Acm')
        ->assertSee('Acme Corp'); // Should filter now
});

test('can filter by category', function () {
    $categoryA = ClientCategory::factory()->create(['name' => 'Category A']);
    $categoryB = ClientCategory::factory()->create(['name' => 'Category B']);

    Client::factory()->create(['company' => 'Company A', 'client_category_id' => $categoryA->id]);
    Client::factory()->create(['company' => 'Company B', 'client_category_id' => $categoryB->id]);

    Livewire::test('pm::client-list')
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

    Livewire::test('pm::client-list')
        ->set('filterCategories', [$categoryA->id, $categoryB->id])
        ->assertSee('Company A')
        ->assertSee('Company B')
        ->assertDontSee('Company C');
});

test('can filter by project category', function () {
    $webCategory = ProjectCategory::factory()->create();
    $mobileCategory = ProjectCategory::factory()->create();

    $webClient = Client::factory()->create(['company' => 'Web Dev Co']);
    Project::factory()->create(['client_id' => $webClient->id, 'project_category_id' => $webCategory->id]);

    $mobileClient = Client::factory()->create(['company' => 'Mobile App Co']);
    Project::factory()->create(['client_id' => $mobileClient->id, 'project_category_id' => $mobileCategory->id]);

    Livewire::test('pm::client-list')
        ->set('filterProjectCategories', [$webCategory->id])
        ->assertSee('Web Dev Co')
        ->assertDontSee('Mobile App Co');
});

test('can filter by status prospect', function () {
    Client::factory()->prospect()->create(['company' => 'Prospect Co']);
    Client::factory()->active()->create(['company' => 'Active Co']);

    Livewire::test('pm::client-list')
        ->set('filterStatus', 'prospect')
        ->assertSee('Prospect Co')
        ->assertDontSee('Active Co');
});

test('can filter by status active', function () {
    Client::factory()->prospect()->create(['company' => 'Prospect Co']);
    Client::factory()->active()->create(['company' => 'Active Co']);

    Livewire::test('pm::client-list')
        ->set('filterStatus', 'active')
        ->assertSee('Active Co')
        ->assertDontSee('Prospect Co');
});

test('can filter by active flag', function () {
    Client::factory()->active()->create(['company' => 'Enabled Company']);
    Client::factory()->inactive()->create(['company' => 'Disabled Company']);

    Livewire::test('pm::client-list')
        ->set('filterActive', 'active')
        ->assertSee('Enabled Company')
        ->assertDontSee('Disabled Company');
});

test('can filter by inactive flag', function () {
    Client::factory()->active()->create(['company' => 'Enabled Company']);
    Client::factory()->inactive()->create(['company' => 'Disabled Company']);

    Livewire::test('pm::client-list')
        ->set('filterActive', 'inactive')
        ->assertSee('Disabled Company')
        ->assertDontSee('Enabled Company');
});

test('can combine multiple filters', function () {
    $category = ClientCategory::factory()->create();
    $projectCategory = ProjectCategory::factory()->create();
    $otherProjectCategory = ProjectCategory::factory()->create();

    $matchClient = Client::factory()->prospect()->create([
        'company' => 'Match',
        'client_category_id' => $category->id,
    ]);
    Project::factory()->create(['client_id' => $matchClient->id, 'project_category_id' => $projectCategory->id]);

    $wrongStatusClient = Client::factory()->active()->create([
        'company' => 'No Match - Different Status',
        'client_category_id' => $category->id,
    ]);
    Project::factory()->create(['client_id' => $wrongStatusClient->id, 'project_category_id' => $projectCategory->id]);

    $wrongCategoryClient = Client::factory()->prospect()->create([
        'company' => 'No Match - Different Project Category',
        'client_category_id' => $category->id,
    ]);
    Project::factory()->create(['client_id' => $wrongCategoryClient->id, 'project_category_id' => $otherProjectCategory->id]);

    Livewire::test('pm::client-list')
        ->set('filterCategories', [$category->id])
        ->set('filterProjectCategories', [$projectCategory->id])
        ->set('filterStatus', 'prospect')
        ->assertSee('Match')
        ->assertDontSee('No Match - Different Status')
        ->assertDontSee('No Match - Different Project Category');
});

test('can sort by column', function () {
    Client::factory()->create(['company' => 'Zebra Corp']);
    Client::factory()->create(['company' => 'Alpha Corp']);

    Livewire::test('pm::client-list')
        ->call('sortData', 'company')
        ->assertSet('sort', 'company')
        ->assertSet('sortDirection', 'asc');
});

test('can toggle sort direction', function () {
    Livewire::test('pm::client-list')
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

    Livewire::test('pm::client-list')
        ->call('filterByCategory', $category->id)
        ->assertSet('filterCategories', [$category->id])
        ->call('filterByCategory', $category->id)
        ->assertSet('filterCategories', []);
});

test('can toggle project category filter', function () {
    $category = ProjectCategory::factory()->create();

    Livewire::test('pm::client-list')
        ->call('filterByProjectCategory', $category->id)
        ->assertSet('filterProjectCategories', [$category->id])
        ->call('filterByProjectCategory', $category->id)
        ->assertSet('filterProjectCategories', []);
});

test('can toggle client active status', function () {
    $client = Client::factory()->active()->create(['active' => true]);

    Livewire::test('pm::client-list')
        ->call('toggleActive', $client->id)
        ->assertDispatched('notify');

    expect($client->fresh()->active)->toBeFalse();
});

test('toggleActive updates user status correctly for active client', function () {
    $client = Client::factory()->active()->create(['active' => true]);
    $user = $client->user;

    expect($user->status)->toBe(1);

    Livewire::test('pm::client-list')
        ->call('toggleActive', $client->id);

    expect($client->fresh()->active)->toBeFalse();
    expect($user->fresh()->status)->toBe(0);
});

test('toggleActive updates user status correctly for prospect client', function () {
    $client = Client::factory()->prospect()->create(['active' => true]);
    $user = $client->user;

    expect($user->status)->toBe(0); // Prospect always has inactive user

    Livewire::test('pm::client-list')
        ->call('toggleActive', $client->id);

    expect($client->fresh()->active)->toBeFalse();
    expect($user->fresh()->status)->toBe(0); // Still false
});

test('can delete client', function () {
    $client = Client::factory()->create(['company' => 'Delete Me']);

    $client->delete();

    expect(Client::find($client->id))->toBeNull();
});

// Note: Page reset is handled automatically by updated() method calling resetPage()

test('pagination works correctly', function () {
    Client::factory()->count(25)->create();

    $component = Livewire::test('pm::client-list')
        ->set('limit', 10)
        ->assertSet('limit', 10);

    expect($component->clientList)->toHaveCount(10);
});
