<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
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
        'email' => 'project-category-api-test@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $this->user->roles()->attach($this->role);

    RolePermission::factory()
        ->forRole($this->role)
        ->forModule('pm', 'master', 'project_category')
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
        'credential' => 'project-category-api-test@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => $this->clientToken,
    ]);

    $this->accessToken = $loginResponse->json('data.access_token');
});

test('list returns all project categories without pagination', function () {
    ProjectCategory::factory()->count(3)->create();

    $response = $this->getJson('/api/pm/project-category/list', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    expect($response->json())->not->toHaveKey('meta');
    expect($response->json('data'))->toHaveCount(3);
});

test('list returns categories ordered by name', function () {
    ProjectCategory::factory()->create(['name' => 'Zebra']);
    ProjectCategory::factory()->create(['name' => 'Alpha']);

    $response = $this->getJson('/api/pm/project-category/list', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful();
    expect($response->json('data.0.name'))->toBe('Alpha');
    expect($response->json('data.1.name'))->toBe('Zebra');
});

test('list searches by name', function () {
    ProjectCategory::factory()->create(['name' => 'Findable Category']);
    ProjectCategory::factory()->count(2)->create();

    $response = $this->getJson('/api/pm/project-category/list?q=Findable', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.name'))->toBe('Findable Category');
});

test('list searches by code', function () {
    $category = ProjectCategory::factory()->create(['code' => 'ABC-123']);
    ProjectCategory::factory()->count(2)->create();

    $response = $this->getJson('/api/pm/project-category/list?q=ABC-123', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($category->id);
});

test('unauthenticated request returns 401', function () {
    $response = $this->getJson('/api/pm/project-category/list');

    $response->assertUnauthorized();
});

test('user without permission gets 403', function () {
    $userRole = Role::factory()->user()->create();
    $user = User::factory()->create([
        'email' => 'project-category-noperm@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $user->roles()->attach($userRole);

    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'project-category-noperm@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => $this->clientToken,
    ]);

    $token = $loginResponse->json('data.access_token');

    $response = $this->getJson('/api/pm/project-category/list', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertForbidden();
});
