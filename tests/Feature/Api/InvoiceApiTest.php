<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\SynAuth\Models\Role;
use VmEngine\SynAuth\Models\RolePermission;
use VmEngine\SynAuth\Models\User;

beforeEach(function () {
    Config::set('synapps.auth.login_credential', 'both');
    Config::set('synapps.auth.features.email_verification', false);
    Config::set('synapps.auth.features.two_factor', false);

    // Create role with invoice report read permission
    $this->role = Role::factory()->admin()->create();

    $this->user = User::factory()->create([
        'email' => 'api-test@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $this->user->roles()->attach($this->role);

    RolePermission::factory()
        ->forRole($this->role)
        ->forModule('pm', 'report', 'invoice')
        ->create([
            'can_create' => false,
            'can_read' => true,
            'can_update' => false,
            'can_delete' => false,
        ]);

    // Login and get token
    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'api-test@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ]);

    $this->accessToken = $loginResponse->json('data.access_token');
});

// =====================================================
// Helper
// =====================================================

function apiGet(string $uri, array $headers = [])
{
    return test()->getJson("/api/pm/invoice{$uri}", array_merge([
        'Authorization' => 'Bearer '.test()->accessToken,
    ], $headers));
}

function createInvoices(int $count = 3, array $overrides = []): void
{
    $client = Client::factory()->create();
    $category = ProjectCategory::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);

    $defaults = [
        'project_id' => $project->id,
        'project_category_id' => $category->id,
        'client_id' => $client->id,
        'created_by' => test()->user->id,
        'archived' => false,
    ];

    ProjectInvoice::factory()->count($count)->create(array_merge($defaults, $overrides));
}

// =====================================================
// Statistics Endpoint
// =====================================================

test('statistics returns expected fields', function () {
    createInvoices(3, ['issue_date' => now()]);

    $response = apiGet('/statistics?year='.now()->year);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'total_invoice',
                'total_invoice_categories',
                'total_invoice_creator',
                'grand_total_amount',
                'grand_total_unpaid',
                'grand_total_paid',
                'total_vat',
                'total_amount',
                'unpaid_vat',
                'paid_vat',
                'unpaid_amount',
                'paid_amount',
            ],
        ]);

    expect($response->json('data.total_invoice'))->toBe(3);
    expect($response->json('status'))->toStartWith('200');
});

test('statistics works without year filter', function () {
    createInvoices(2, ['issue_date' => now()]);

    $response = apiGet('/statistics');

    $response->assertSuccessful();
    expect($response->json('data.total_invoice'))->toBe(2);
});

test('statistics returns proper error for invalid year', function () {
    $response = apiGet('/statistics?year=abc');

    $response->assertStatus(422);
    expect($response->json('status'))->toStartWith('422');
});

// =====================================================
// By Creator Endpoint
// =====================================================

test('by-creator returns grouped data', function () {
    createInvoices(3, ['issue_date' => now()]);

    $response = apiGet('/by-creator?year='.now()->year);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
});

test('by-creator filters by creator id', function () {
    createInvoices(3, ['issue_date' => now()]);

    $response = apiGet('/by-creator?year='.now()->year.'&id='.test()->user->id);

    $response->assertSuccessful();
    $data = $response->json('data');
    expect($data)->toHaveCount(1);
});

// =====================================================
// By Project Category Endpoint
// =====================================================

test('by-project-category returns grouped data', function () {
    createInvoices(3, ['issue_date' => now()]);

    $response = apiGet('/by-project-category?year='.now()->year);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
});

// =====================================================
// By Month Endpoint
// =====================================================

test('by-month returns monthly breakdown', function () {
    createInvoices(3, ['issue_date' => now()]);

    $response = apiGet('/by-month?year='.now()->year);

    $response->assertSuccessful();
    $data = $response->json('data');
    expect($data)->toBeArray();

    if (count($data) > 0) {
        expect($data[0])->toHaveKeys(['year', 'month', 'total_count', 'total_amount']);
    }
});

// =====================================================
// Invoice List Endpoint
// =====================================================

test('list returns paginated invoices', function () {
    createInvoices(5, ['issue_date' => now()]);

    $response = apiGet('/list?year='.now()->year.'&limit=2');

    $response->assertSuccessful()
        ->assertJsonStructure([
            'status',
            'message',
            'data',
            'meta' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);

    expect($response->json('meta.per_page'))->toBe(2);
    expect($response->json('meta.total'))->toBe(5);
});

test('list filters by is_paid', function () {
    createInvoices(3, ['issue_date' => now(), 'is_paid' => true]);
    createInvoices(2, ['issue_date' => now(), 'is_paid' => false]);

    $response = apiGet('/list?year='.now()->year.'&is_paid=1');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(3);
});

test('list filters by month', function () {
    createInvoices(2, ['issue_date' => now()->startOfYear()->addMonths(0)]);
    createInvoices(3, ['issue_date' => now()->startOfYear()->addMonths(5)]);

    $response = apiGet('/list?year='.now()->year.'&month=1');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(2);
});

test('list returns invoice resource fields', function () {
    createInvoices(1, ['issue_date' => now()]);

    $response = apiGet('/list?year='.now()->year);

    $response->assertSuccessful();
    $invoice = $response->json('data.0');
    expect($invoice)->toHaveKeys([
        'id', 'invoice_number', 'issue_date', 'due_date',
        'recipient', 'amount', 'vat_amount', 'total_amount',
        'is_paid', 'project', 'project_category', 'creator', 'client',
    ]);
});

test('list validates sort_by parameter', function () {
    $response = apiGet('/list?sort_by=invalid_column');

    $response->assertStatus(422);
});

test('list searches by invoice number', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $category = ProjectCategory::factory()->create();

    ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'project_category_id' => $category->id,
        'client_id' => $client->id,
        'created_by' => test()->user->id,
        'archived' => false,
        'invoice_number' => 'INV-FINDME-001',
    ]);
    createInvoices(3);

    $response = apiGet('/list?q=FINDME');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
    expect($response->json('data.0.invoice_number'))->toBe('INV-FINDME-001');
});

test('list searches by receipt number', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $category = ProjectCategory::factory()->create();

    ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'project_category_id' => $category->id,
        'client_id' => $client->id,
        'created_by' => test()->user->id,
        'archived' => false,
        'is_paid' => true,
        'receipt_number' => 'RCP-FINDME-999',
    ]);
    createInvoices(3);

    $response = apiGet('/list?q=RCP-FINDME');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
    expect($response->json('data.0.receipt_number'))->toBe('RCP-FINDME-999');
});

test('list searches by client name', function () {
    $client = Client::factory()->create(['company' => 'PT Searchable Company']);
    $project = Project::factory()->create(['client_id' => $client->id]);
    $category = ProjectCategory::factory()->create();

    ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'project_category_id' => $category->id,
        'client_id' => $client->id,
        'created_by' => test()->user->id,
        'archived' => false,
    ]);
    createInvoices(3);

    $response = apiGet('/list?q=Searchable');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
    expect($response->json('data.0.client.company'))->toBe('PT Searchable Company');
});

test('list searches by creator name', function () {
    $creator = User::factory()->create(['name' => 'Unique Creator Name']);
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $category = ProjectCategory::factory()->create();

    ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'project_category_id' => $category->id,
        'client_id' => $client->id,
        'created_by' => $creator->id,
        'archived' => false,
    ]);
    createInvoices(3);

    $response = apiGet('/list?q=Unique+Creator');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(1);
    expect($response->json('data.0.creator.name'))->toBe('Unique Creator Name');
});

test('list q with no match returns empty results', function () {
    createInvoices(3);

    $response = apiGet('/list?q=ZZZNOMATCHZZZ');

    $response->assertSuccessful();
    expect($response->json('meta.total'))->toBe(0);
});

// =====================================================
// Authentication Tests
// =====================================================

test('unauthenticated request returns 401', function () {
    $response = $this->getJson('/api/pm/invoice/statistics');

    $response->assertUnauthorized();
});

// =====================================================
// Permission Tests
// =====================================================

test('user without permission gets 403', function () {
    $userRole = Role::factory()->user()->create();
    $user = User::factory()->create([
        'email' => 'noperm@example.com',
        'password' => Hash::make('password123'),
        'two_factor_enabled' => false,
    ]);
    $user->roles()->attach($userRole);

    // No pm.report.invoice.read permission

    $loginResponse = $this->postJson('/api/auth/login', [
        'credential' => 'noperm@example.com',
        'password' => 'password123',
        'device_name' => 'Test Device',
    ]);

    $token = $loginResponse->json('data.access_token');

    $response = $this->getJson('/api/pm/invoice/statistics', [
        'Authorization' => 'Bearer '.$token,
    ]);

    $response->assertForbidden();
});
