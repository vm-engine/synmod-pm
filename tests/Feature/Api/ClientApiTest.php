<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;
use VmEngine\SynAuth\Http\Middleware\Api\ValidateClientToken;
use VmEngine\SynAuth\Models\ApiToken;
use VmEngine\SynAuth\Models\Role;
use VmEngine\SynAuth\Models\RolePermission;
use VmEngine\SynAuth\Models\User;

uses(RefreshDatabase::class);

beforeEach(function () {
    Config::set('synapps.auth.login_credential', 'both');
    Config::set('synapps.auth.features.email_verification', false);
    Config::set('synapps.auth.features.two_factor', false);

    $this->role = Role::factory()->admin()->create();

    $this->user = User::factory()->create([
        'email' => 'client-api-test@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $this->user->roles()->attach($this->role);

    RolePermission::factory()
        ->forRole($this->role)
        ->forModule('pm', 'client')
        ->create([
            'can_create' => false,
            'can_read' => true,
            'can_update' => false,
            'can_delete' => false,
        ]);

    $apiUser = User::factory()->create([
        'is_api_user' => true,
        'email_verified_at' => now(),
        'two_factor_enabled' => false,
    ]);
    $newToken = $apiUser->createToken('test-client-token');
    $clientToken = $newToken->plainTextToken;
    $newToken->accessToken->update([
        'type' => ApiToken::TYPE_CLIENT,
        'expires_at' => null,
        'allowed_ips' => null,
    ]);
    $this->clientToken = $clientToken;

    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'client-api-test@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => $this->clientToken,
    ]);

    $this->accessToken = $loginResponse->json('data.access_token');
});

function clientApiGet(string $uri, array $headers = [])
{
    return test()->getJson("/api/pm/client{$uri}", array_merge([
        'Authorization' => 'Bearer '.test()->accessToken,
    ], $headers));
}

// =====================================================
// List Endpoint
// =====================================================

test('list returns paginated clients', function () {
    Client::factory()->count(5)->create();

    $response = clientApiGet('/list?limit=2');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);

    expect($response->json('meta.per_page'))->toBe(2);
    expect($response->json('meta.total'))->toBe(5);
});

test('list returns client resource fields', function () {
    $category = ClientCategory::factory()->create(['name' => 'Retail']);
    Client::factory()->create(['client_category_id' => $category->id, 'services' => [1, 2]]);

    $response = clientApiGet('/list');

    $response->assertSuccessful();
    $client = $response->json('data.0');
    expect($client)->toHaveKeys([
        'id', 'company', 'address', 'city', 'director', 'pic', 'phone',
        'status', 'active', 'is_proposal', 'social_media', 'services', 'category', 'created_at',
    ]);
    expect($client['category']['name'])->toBe('Retail');
    expect($client['services'])->toBe([
        ['id' => 1, 'label' => 'Company Profile'],
        ['id' => 2, 'label' => 'Web Application'],
    ]);
});

test('list filters by client_category_id', function () {
    $categoryA = ClientCategory::factory()->create();
    $categoryB = ClientCategory::factory()->create();
    Client::factory()->count(2)->create(['client_category_id' => $categoryA->id]);
    Client::factory()->count(3)->create(['client_category_id' => $categoryB->id]);

    $response = clientApiGet('/list?client_category_id='.$categoryA->id);

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(2);
});

test('list filters by status', function () {
    Client::factory()->count(2)->create(['status' => Client::STATUS_PROSPECT]);
    Client::factory()->count(3)->create(['status' => Client::STATUS_ACTIVE]);

    $response = clientApiGet('/list?status='.Client::STATUS_ACTIVE);

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(3);
});

test('list filters by active flag', function () {
    Client::factory()->count(2)->create(['active' => false]);
    Client::factory()->count(3)->create(['active' => true]);

    $response = clientApiGet('/list?active=1');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(3);
});

test('list filters by services any-match', function () {
    Client::factory()->create(['services' => [1]]);
    Client::factory()->create(['services' => [2]]);
    Client::factory()->create(['services' => [1, 3]]);

    $response = clientApiGet('/list?services[]=1');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(2);
});

test('list filters by project_category any-match', function () {
    $categoryA = ProjectCategory::factory()->create();
    $categoryB = ProjectCategory::factory()->create();

    $clientA = Client::factory()->create();
    Project::factory()->create(['client_id' => $clientA->id, 'project_category_id' => $categoryA->id]);

    $clientB = Client::factory()->create();
    Project::factory()->create(['client_id' => $clientB->id, 'project_category_id' => $categoryB->id]);

    $clientC = Client::factory()->create();
    Project::factory()->create(['client_id' => $clientC->id, 'project_category_id' => $categoryA->id]);

    $response = clientApiGet('/list?project_category[]='.$categoryA->id);

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(2);
});

test('list searches by company name', function () {
    Client::factory()->create(['company' => 'PT Searchable Company']);
    Client::factory()->count(3)->create();

    $response = clientApiGet('/list?q=Searchable');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
    expect($response->json('data.0.company'))->toBe('PT Searchable Company');
});

test('list validates sort_by parameter', function () {
    $response = clientApiGet('/list?sort_by=invalid_column');

    $response->assertStatus(422);
});

// =====================================================
// Detail Endpoint
// =====================================================

test('show returns client detail', function () {
    $category = ClientCategory::factory()->create(['name' => 'Manufacturing']);
    $client = Client::factory()->create(['client_category_id' => $category->id, 'company' => 'PT Detail Test']);

    $response = clientApiGet('/'.$client->id);

    $response->assertSuccessful();
    expect($response->json('data.id'))->toBe($client->id);
    expect($response->json('data.company'))->toBe('PT Detail Test');
    expect($response->json('data.category.name'))->toBe('Manufacturing');
});

test('show returns 404 for missing client', function () {
    $response = clientApiGet('/999999');

    $response->assertNotFound()
        ->assertJsonStructure(['status', 'message'])
        ->assertJson(['message' => 'Client not found.']);
    expect($response->json('status'))->toStartWith('404');
});

test('show returns 404 for non-numeric id without a stack trace', function () {
    $response = clientApiGet('/not-a-number');

    $response->assertNotFound()
        ->assertJsonStructure(['status', 'message'])
        ->assertJson(['message' => 'Client not found.']);
    expect($response->json())->not->toHaveKey('exception');
    expect($response->json())->not->toHaveKey('trace');
});

// =====================================================
// Auth / Permission
// =====================================================

test('unauthenticated request returns 401', function () {
    $response = $this->getJson('/api/pm/client/list');

    $response->assertUnauthorized();
});

test('user without permission gets 403', function () {
    $userRole = Role::factory()->user()->create();
    $user = User::factory()->create([
        'email' => 'client-noperm@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $user->roles()->attach($userRole);

    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'client-noperm@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => test()->clientToken,
    ]);

    $token = $loginResponse->json('data.access_token');

    $response = $this->getJson('/api/pm/client/list', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertForbidden();
});
