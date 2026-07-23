<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use VmEngine\Pm\Models\ClientCategory;
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
        'email' => 'client-category-api-test@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $this->user->roles()->attach($this->role);

    RolePermission::factory()
        ->forRole($this->role)
        ->forModule('pm', 'client')
        ->create(['can_read' => true]);

    $apiUser = User::factory()->create([
        'is_api_user' => true,
        'email_verified_at' => now(),
        'two_factor_enabled' => false,
    ]);
    $newToken = $apiUser->createToken('test-client-token');
    $this->clientToken = $newToken->plainTextToken;
    $newToken->accessToken->update([
        'type' => ApiToken::TYPE_CLIENT,
        'expires_at' => null,
        'allowed_ips' => null,
    ]);

    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'client-category-api-test@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => $this->clientToken,
    ]);

    $this->accessToken = $loginResponse->json('data.access_token');
});

test('list returns paginated client categories', function () {
    ClientCategory::factory()->count(3)->create();

    $response = $this->getJson('/api/pm/client-category/list', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
    expect($response->json('meta.total'))->toBe(3);
});

test('list searches by name', function () {
    ClientCategory::factory()->create(['name' => 'Findable Category']);
    ClientCategory::factory()->count(2)->create();

    $response = $this->getJson('/api/pm/client-category/list?q=Findable', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
});
