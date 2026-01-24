<?php

declare(strict_types=1);

use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;
use VmEngine\Pm\Models\Project;

test('client has correct fillable fields', function () {
    $fillable = [
        'user_id',
        'client_category_id',
        'company',
        'address',
        'city',
        'director',
        'pic',
        'phone',
        'services',
        'social_media',
        'npwp_image',
        'status',
        'active',
        'is_proposal',
        'created_by',
        'updated_by',
    ];

    expect((new Client)->getFillable())->toBe($fillable);
});

test('client casts attributes correctly', function () {
    $client = Client::factory()->create([
        'services' => [1, 2, 3],
        'social_media' => ['website', 'linkedin'],
        'status' => 1,
        'active' => true,
        'is_proposal' => false,
    ]);

    expect($client->services)->toBeArray();
    expect($client->social_media)->toBeArray();
    expect($client->status)->toBeInt();
    expect($client->active)->toBeBool();
    expect($client->is_proposal)->toBeBool();
});

test('client belongs to category', function () {
    $category = ClientCategory::factory()->create();
    $client = Client::factory()->create(['client_category_id' => $category->id]);

    expect($client->category)->toBeInstanceOf(ClientCategory::class);
    expect($client->category->id)->toBe($category->id);
});

test('client belongs to user', function () {
    $client = Client::factory()->create();

    expect($client->user)->not->toBeNull();
    expect($client->user_id)->toBe($client->user->id);
});

test('client has creator relationship', function () {
    $UserModel = config('auth.providers.users.model');
    $creator = $UserModel::factory()->create();
    $client = Client::factory()->create(['created_by' => $creator->id]);

    expect($client->creator)->not->toBeNull();
    expect($client->creator->id)->toBe($creator->id);
});

test('client has updater relationship', function () {
    $UserModel = config('auth.providers.users.model');
    $updater = $UserModel::factory()->create();
    $client = Client::factory()->create(['updated_by' => $updater->id]);

    expect($client->updater)->not->toBeNull();
    expect($client->updater->id)->toBe($updater->id);
});

test('client has projects relationship', function () {
    $client = Client::factory()->create();
    Project::factory()->create(['user_id' => $client->user_id]);

    expect($client->projects)->toHaveCount(1);
    expect($client->projects->first())->toBeInstanceOf(Project::class);
});

test('isProspect returns true for prospect status', function () {
    $client = Client::factory()->prospect()->create();

    expect($client->isProspect())->toBeTrue();
});

test('isProspect returns false for active status', function () {
    $client = Client::factory()->active()->create();

    expect($client->isProspect())->toBeFalse();
});

test('isActive returns true for active status', function () {
    $client = Client::factory()->active()->create();

    expect($client->isActive())->toBeTrue();
});

test('isActive returns false for prospect status', function () {
    $client = Client::factory()->prospect()->create();

    expect($client->isActive())->toBeFalse();
});

test('prospect scope filters prospect clients', function () {
    Client::factory()->prospect()->create();
    Client::factory()->active()->create();

    $prospects = Client::prospect()->get();

    expect($prospects)->toHaveCount(1);
    expect($prospects->first()->status)->toBe(Client::STATUS_PROSPECT);
});

test('activeStatus scope filters active status clients', function () {
    Client::factory()->prospect()->create();
    Client::factory()->active()->create();

    $activeClients = Client::activeStatus()->get();

    expect($activeClients)->toHaveCount(1);
    expect($activeClients->first()->status)->toBe(Client::STATUS_ACTIVE);
});

test('active scope filters active clients', function () {
    Client::factory()->create(['active' => true]);
    Client::factory()->inactive()->create();

    $activeClients = Client::active()->get();

    expect($activeClients)->toHaveCount(1);
    expect($activeClients->first()->active)->toBeTrue();
});

test('inactive scope filters inactive clients', function () {
    Client::factory()->create(['active' => true]);
    Client::factory()->inactive()->create();

    $inactiveClients = Client::inactive()->get();

    expect($inactiveClients)->toHaveCount(1);
    expect($inactiveClients->first()->active)->toBeFalse();
});

test('search scope finds clients by company', function () {
    Client::factory()->create(['company' => 'Acme Corporation']);
    Client::factory()->create(['company' => 'Tech Solutions']);

    $results = Client::search('Acme')->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->company)->toBe('Acme Corporation');
});

test('search scope finds clients by address', function () {
    Client::factory()->create(['address' => '123 Main Street']);
    Client::factory()->create(['address' => '456 Oak Avenue']);

    $results = Client::search('Main')->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->address)->toBe('123 Main Street');
});

test('search scope finds clients by city', function () {
    Client::factory()->create(['city' => 'New York']);
    Client::factory()->create(['city' => 'Los Angeles']);

    $results = Client::search('York')->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->city)->toBe('New York');
});

test('search scope finds clients by director', function () {
    Client::factory()->create([
        'company' => 'Alpha Corp',
        'address' => '123 Main St',
        'city' => 'Boston',
        'director' => 'John Smith',
        'pic' => 'Alice Brown',
    ]);
    Client::factory()->create([
        'company' => 'Beta Inc',
        'address' => '456 Oak Ave',
        'city' => 'Seattle',
        'director' => 'Jane Doe',
        'pic' => 'Bob Wilson',
    ]);

    $results = Client::search('John')->get();

    expect($results->toArray())->toHaveCount(1);
    expect($results->first()->director)->toBe('John Smith');
});

test('search scope finds clients by pic', function () {
    Client::factory()->create([
        'company' => 'Gamma Ltd',
        'address' => '789 Pine Rd',
        'city' => 'Denver',
        'director' => 'Robert Brown',
        'pic' => 'Alice Johnson',
    ]);
    Client::factory()->create([
        'company' => 'Delta Corp',
        'address' => '321 Elm St',
        'city' => 'Phoenix',
        'director' => 'Sarah Davis',
        'pic' => 'Bob Williams',
    ]);

    $results = Client::search('Alice')->get();

    expect($results->toArray())->toHaveCount(1);
    expect($results->first()->pic)->toBe('Alice Johnson');
});

test('search scope is case insensitive', function () {
    Client::factory()->create(['company' => 'Acme Corporation']);

    expect(Client::search('acme')->get())->toHaveCount(1);
    expect(Client::search('ACME')->get())->toHaveCount(1);
    expect(Client::search('AcMe')->get())->toHaveCount(1);
});

test('byServiceAny scope filters by single service', function () {
    Client::factory()->create(['services' => [1, 2]]);
    Client::factory()->create(['services' => [3, 4]]);

    $results = Client::byServiceAny([1])->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->services)->toContain(1);
});

test('byServiceAny scope filters by multiple services with ANY match', function () {
    Client::factory()->create(['company' => 'Client A', 'services' => [1, 2]]);
    Client::factory()->create(['company' => 'Client B', 'services' => [3, 4]]);
    Client::factory()->create(['company' => 'Client C', 'services' => [1, 5]]);

    $results = Client::byServiceAny([1, 3])->get();

    expect($results)->toHaveCount(3);
});

test('byServiceAny scope returns all when empty array', function () {
    Client::factory()->count(3)->create();

    $results = Client::byServiceAny([])->get();

    expect($results)->toHaveCount(3);
});

test('delete token can be generated and validated', function () {
    $client = Client::factory()->create();
    $token = $client->generateDeleteToken($client->id);

    expect($token)->toBeString();
    expect(Client::validateDeleteToken($token))->toBe((string) $client->id);
});

test('delete token validation fails for invalid token', function () {
    expect(Client::validateDeleteToken('invalid-token'))->toBeFalse();
});

test('delete token validation fails when session is missing', function () {
    $client = Client::factory()->create();

    // Clear session
    session()->forget('dToken');

    expect(Client::validateDeleteToken('some-token'))->toBeFalse();
});

test('can combine multiple scopes', function () {
    Client::factory()->prospect()->create(['company' => 'Prospect A', 'active' => true]);
    Client::factory()->prospect()->inactive()->create(['company' => 'Prospect B']);
    Client::factory()->active()->create(['company' => 'Active A', 'active' => true]);

    $results = Client::prospect()->active()->get();

    expect($results)->toHaveCount(1);
    expect($results->first()->company)->toBe('Prospect A');
});
