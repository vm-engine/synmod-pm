<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Livewire\Project\ReceiptDetail;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\Signature;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());

    // Setup document configuration for receipt generation
    setupDocumentConfig();
});

function setupDocumentConfig(): void
{
    // Create a project category for testing
    $category = \VmEngine\Pm\Models\ProjectCategory::firstOrCreate(
        ['code' => 'TEST'],
        ['name' => 'Test Category', 'description' => 'Test category for unit tests']
    );

    // Setup document number format configuration
    // Note: Documents::RECEIPT->code(true) returns 'rcp' (lowercase)
    dbconf([
        'pm.documents.rcp.number_format' => 'RCP-{YYYY}-{MM}-{CNT}',
        'pm.documents.'.$category->code.'.rcp.next_c_index' => 1,
        'pm.documents.'.$category->code.'.rcp.next_nc_index' => 1,
    ]);
}

function createTestProject(array $attributes = []): Project
{
    $category = \VmEngine\Pm\Models\ProjectCategory::where('code', 'TEST')->first();

    return Project::factory()->create(array_merge([
        'project_category_id' => $category->id,
    ], $attributes));
}

test('component can be rendered', function () {
    Livewire::test(ReceiptDetail::class)
        ->assertStatus(200);
});

test('loads invoice data when dispatched', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'invoice_number' => 'INV-001',
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->assertSet('invoice.invoice_number', 'INV-001')
        ->assertSet('invoiceId', $invoice->id);
});

test('generates receipt number preview', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
    ]);

    $component = Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id);

    expect($component->get('receiptNumber'))->not->toBeEmpty();
});

test('generates amount text preview', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id);

    expect($component->get('amountText'))->toContain('Rupiah');
});

test('can generate receipt pdf', function () {
    Storage::fake('local');

    $issuer = DocumentIssuer::factory()->create();
    $signature = Signature::factory()->create();
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'issuer_id' => $issuer->id,
        'signature_id' => $signature->id,
        'has_signature' => true,
        'total_amount' => 1000000,
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->call('generateReceipt')
        ->assertHasNoErrors();

    // Check receipt number was set
    expect($invoice->fresh()->receipt_number)->not->toBeNull();

    // Check amount text was set
    expect($invoice->fresh()->receipt_amount_text)->toContain('Rupiah');

    // Check document was created
    expect($invoice->fresh()->receipt_document_id)->not->toBeNull();
});

test('displays has receipt status correctly', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_document_id' => 1,
    ]);

    $component = Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id);

    expect($component->get('hasReceipt'))->toBeTrue();
});

test('payment status checkbox updates correctly', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'is_paid' => false,
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->assertSet('isPaid', false)
        ->set('isPaid', true)
        ->assertSet('isPaid', true)
        ->assertSet('paidAt', function ($value) {
            return $value !== null;
        });
});

test('unchecking payment status clears paid at field', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'is_paid' => true,
        'paid_at' => now(),
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', false)
        ->assertSet('paidAt', null);
});

test('can save payment status with datetime', function () {
    Storage::fake('local');

    $issuer = DocumentIssuer::factory()->create();
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'issuer_id' => $issuer->id,
        'total_amount' => 1000000,
    ]);

    // Generate receipt first
    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->call('generateReceipt');

    // Now save payment status
    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', true)
        ->set('paidAt', '2024-01-15 10:30')
        ->call('savePaymentStatus')
        ->assertHasNoErrors();

    expect($invoice->fresh()->is_paid)->toBeTrue();
    expect($invoice->fresh()->paid_at)->not->toBeNull();
});

test('can save payment status with proof file', function () {
    Storage::fake('local');

    $issuer = DocumentIssuer::factory()->create();
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'issuer_id' => $issuer->id,
        'total_amount' => 1000000,
    ]);

    // Generate receipt first
    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->call('generateReceipt');

    $file = UploadedFile::fake()->create('payment-proof.pdf', 1000, 'application/pdf');

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', true)
        ->set('paidAt', '2024-01-15 10:30')
        ->set('paymentProof', $file)
        ->call('savePaymentStatus')
        ->assertHasNoErrors();

    expect($invoice->fresh()->payment_proof)->not->toBeNull();
    Storage::disk('local')->assertExists($invoice->fresh()->payment_proof);
});

test('cannot save payment status without generating receipt first', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'receipt_document_id' => null,
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', true)
        ->set('paidAt', '2024-01-15 10:30')
        ->call('savePaymentStatus')
        ->assertDispatched('notify');
});

test('validates paid at is required when marking as paid', function () {
    Storage::fake('local');

    $issuer = DocumentIssuer::factory()->create();
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'issuer_id' => $issuer->id,
    ]);

    // Generate receipt first
    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->call('generateReceipt');

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', true)
        ->set('paidAt', '')
        ->call('savePaymentStatus')
        ->assertHasErrors(['paidAt']);
});

test('validates payment proof file type', function () {
    Storage::fake('local');

    $issuer = DocumentIssuer::factory()->create();
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'issuer_id' => $issuer->id,
    ]);

    // Generate receipt first
    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->call('generateReceipt');

    $invalidFile = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->set('isPaid', true)
        ->set('paidAt', '2024-01-15 10:30')
        ->set('paymentProof', $invalidFile)
        ->call('savePaymentStatus')
        ->assertHasErrors(['paymentProof']);
});

test('resets detail on modal close', function () {
    $client = Client::factory()->create();
    $project = createTestProject(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
    ]);

    Livewire::test(ReceiptDetail::class)
        ->dispatch('load-receipt-detail', id: $invoice->id)
        ->assertSet('invoice.id', $invoice->id)
        ->dispatch('close-modal-receipt-detail')
        ->assertSet('invoice', null);
});
