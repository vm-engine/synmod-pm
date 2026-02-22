<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->user = $UserModel::factory()->create();

    // Create dev role with full access (use firstOrCreate to avoid duplicates)
    $devRole = \VmEngine\SynAuth\Models\Role::firstOrCreate(
        ['slug' => 'dev'],
        ['name' => 'Developer', 'level' => 0]
    );
    $this->user->roles()->attach($devRole);

    // Skip ACL middleware for simpler testing
    $this->withoutMiddleware();

    $this->actingAs($this->user);
});

test('can download receipt pdf', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'receipts/receipt-001.pdf',
        'file_info' => ['name' => 'receipt-001.pdf'],
    ]);

    Storage::disk('local')->put('receipts/receipt-001.pdf', 'fake pdf content');

    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_document_id' => $document->id,
    ]);

    $response = $this->get(route('backend.pm.receipt.download', $invoice->id));

    $response->assertStatus(200);
    $response->assertDownload('receipt-001.pdf');
});

test('returns 404 when receipt document does not exist', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_document_id' => null,
    ]);

    $response = $this->get(route('backend.pm.receipt.download', $invoice->id));

    $response->assertStatus(404);
});

test('returns 404 when receipt file does not exist', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'receipts/non-existent.pdf',
    ]);

    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_document_id' => $document->id,
    ]);

    $response = $this->get(route('backend.pm.receipt.download', $invoice->id));

    $response->assertStatus(404);
});

test('can print receipt in browser', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_number' => 'RCP-001',
        'receipt_amount_text' => 'Satu Juta Rupiah',
        'total_amount' => 1000000,
    ]);

    $response = $this->get(route('backend.pm.receipt.print', $invoice->id));

    $response->assertStatus(200);
    $response->assertSee('KUITANSI');
    $response->assertSee('RCP-001');
    $response->assertSee('Satu Juta Rupiah');
});

test('print uses forBrowser flag for image paths', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_number' => 'RCP-001',
    ]);

    $response = $this->get(route('backend.pm.receipt.print', $invoice->id));

    $response->assertStatus(200);
    $response->assertViewHas('forBrowser', true);
});

test('can view payment proof file', function () {
    Storage::disk('local')->put('payment-proofs/proof-001.pdf', 'fake proof content');

    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'payment_proof' => 'payment-proofs/proof-001.pdf',
    ]);

    $response = $this->get(route('backend.pm.receipt.payment-proof', $invoice->id));

    $response->assertStatus(200);
});

test('returns 404 when payment proof does not exist', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'payment_proof' => null,
    ]);

    $response = $this->get(route('backend.pm.receipt.payment-proof', $invoice->id));

    $response->assertStatus(404);
});

test('returns 404 when payment proof file does not exist on disk', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'payment_proof' => 'payment-proofs/non-existent.pdf',
    ]);

    $response = $this->get(route('backend.pm.receipt.payment-proof', $invoice->id));

    $response->assertStatus(404);
});

test('returns 404 for non-existent invoice', function () {
    $this->get(route('backend.pm.receipt.download', 99999))
        ->assertStatus(404);

    $this->get(route('backend.pm.receipt.print', 99999))
        ->assertStatus(404);

    $this->get(route('backend.pm.receipt.payment-proof', 99999))
        ->assertStatus(404);
});
