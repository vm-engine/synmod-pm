<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
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
        'email' => 'service-api-test@example.com',
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
        'credential' => 'service-api-test@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ], [
        ValidateClientToken::HEADER_NAME => $this->clientToken,
    ]);

    $this->accessToken = $loginResponse->json('data.access_token');
});

test('list returns all service enum cases', function () {
    $response = $this->getJson('/api/pm/service/list', [
        'Authorization' => 'Bearer '.$this->accessToken,
    ]);

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(8);
    expect($response->json('data.0'))->toBe(['id' => 1, 'label' => 'Company Profile']);
});
