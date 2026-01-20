<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\Pm\Livewire\Project\InvoiceList;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectInvoice;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test(InvoiceList::class)
        ->assertStatus(200);
});

test('component can be rendered with project id', function () {
    $project = Project::factory()->create();

    Livewire::test(InvoiceList::class, ['projectId' => $project->id])
        ->assertStatus(200)
        ->assertSet('projectId', $project->id);
});

test('loads invoices for specific project', function () {
    $project1 = Project::factory()->create();
    $project2 = Project::factory()->create();

    ProjectInvoice::factory()->count(3)->create(['project_id' => $project1->id]);
    ProjectInvoice::factory()->count(2)->create(['project_id' => $project2->id]);

    $component = Livewire::test(InvoiceList::class, ['projectId' => $project1->id]);

    expect($component->invoiceList)->toHaveCount(3);
});

test('loads all invoices when no project id is set', function () {
    ProjectInvoice::factory()->count(5)->create();

    $component = Livewire::test(InvoiceList::class);

    expect($component->invoiceList)->toHaveCount(5);
});

// Payment Status Tests

test('getPaymentStatus returns paid when is_paid is true', function () {
    $invoice = ProjectInvoice::factory()->create([
        'is_paid' => true,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)->instance();
    $status = $component->getPaymentStatus($invoice);

    expect($status['label'])->toContain('Paid');
    expect($status['class'])->toContain('green');
    expect($status['icon'])->toBe('fa-check-circle');
});

test('getPaymentStatus returns unpaid when is_paid is false', function () {
    $invoice = ProjectInvoice::factory()->create([
        'is_paid' => false,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)->instance();
    $status = $component->getPaymentStatus($invoice);

    expect($status['label'])->toContain('Unpaid');
    expect($status['class'])->toContain('red');
});

// Note: is_paid cannot be null due to NOT NULL constraint in migration

// Partial payment status removed - now only paid/unpaid

test('getPaymentStatus returns unpaid when is_paid is false explicitly', function () {
    $invoice = ProjectInvoice::factory()->create([
        'is_paid' => false,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)->instance();
    $status = $component->getPaymentStatus($invoice);

    expect($status['label'])->toContain('Unpaid');
    expect($status['class'])->toContain('red');
    expect($status['icon'])->toBe('fa-times-circle');
});

test('getPaymentStatus uses is_paid flag', function () {
    // is_paid = true should show as paid
    $invoice = ProjectInvoice::factory()->create([
        'is_paid' => true,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)->instance();
    $status = $component->getPaymentStatus($invoice);

    expect($status['label'])->toContain('Paid');
    expect($status['class'])->toContain('green');
});

// Filter Tests

test('filters paid invoices correctly', function () {
    // Create invoice marked as paid
    $paid = ProjectInvoice::factory()->create([
        'is_paid' => true,
        'total_amount' => 1000000,
    ]);

    // Create unpaid invoice
    $unpaid = ProjectInvoice::factory()->create([
        'is_paid' => false,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterStatus', 'paid');

    $invoices = $component->invoiceList;
    $invoiceIds = $invoices->pluck('id')->toArray();

    expect($invoiceIds)->toContain($paid->id);
    expect($invoiceIds)->not->toContain($unpaid->id);
});

test('filters unpaid invoices correctly', function () {
    // Create paid invoice
    $paid = ProjectInvoice::factory()->create([
        'is_paid' => true,
        'total_amount' => 1000000,
    ]);

    // Create unpaid invoice
    $unpaid = ProjectInvoice::factory()->create([
        'is_paid' => false,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterStatus', 'unpaid');

    $invoices = $component->invoiceList;
    $invoiceIds = $invoices->pluck('id')->toArray();

    expect($invoiceIds)->toContain($unpaid->id);
    expect($invoiceIds)->not->toContain($paid->id);
});

test('partial filter now shows unpaid invoices (partial payment no longer supported)', function () {
    // Create paid invoice
    $paid = ProjectInvoice::factory()->create([
        'is_paid' => true,
        'total_amount' => 1000000,
    ]);

    // Create unpaid invoice
    $unpaid = ProjectInvoice::factory()->create([
        'is_paid' => false,
        'total_amount' => 1000000,
    ]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterStatus', 'partial');

    $invoices = $component->invoiceList;
    $invoiceIds = $invoices->pluck('id')->toArray();

    // Partial filter now acts like unpaid filter
    expect($invoiceIds)->toContain($unpaid->id);
    expect($invoiceIds)->not->toContain($paid->id);
});

test('filters by year', function () {
    $invoice2023 = ProjectInvoice::factory()->create([
        'created_at' => '2023-01-15',
    ]);

    $invoice2024 = ProjectInvoice::factory()->create([
        'created_at' => '2024-06-20',
    ]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterYear', '2024');

    $invoices = $component->invoiceList;
    expect($invoices)->toHaveCount(1);
    expect($invoices->first()->id)->toBe($invoice2024->id);
});

test('filters by issuer', function () {
    $issuer1 = DocumentIssuer::factory()->create();
    $issuer2 = DocumentIssuer::factory()->create();

    $invoice1 = ProjectInvoice::factory()->create(['issuer_id' => $issuer1->id]);
    $invoice2 = ProjectInvoice::factory()->create(['issuer_id' => $issuer2->id]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterIssuer', (string) $issuer1->id);

    $invoices = $component->invoiceList;
    expect($invoices)->toHaveCount(1);
    expect($invoices->first()->id)->toBe($invoice1->id);
});

test('filters by creator', function () {
    $UserModel = config('auth.providers.users.model');
    $creator1 = $UserModel::factory()->create();
    $creator2 = $UserModel::factory()->create();

    // dump($creator1->id, $creator2->id);

    $invoice1 = ProjectInvoice::factory()->create(['created_by' => $creator1->id]);
    $invoice2 = ProjectInvoice::factory()->create(['created_by' => $creator2->id]);

    $component = Livewire::test(InvoiceList::class)
        ->set('filterCreator', (string) $creator1->id)
        ->update();

    $invoices = $component->invoiceList;

    // dd($component->getData(), $component->invoiceList);
    expect($invoices)->toHaveCount(1);
    expect($invoices->first()->id)->toBe($invoice1->id);
});
// })->skip('TODO: Investigate why creator filter returns 0 results');

test('searches by invoice number', function () {
    $invoice1 = ProjectInvoice::factory()->create(['invoice_number' => 'INV-2024-001']);
    $invoice2 = ProjectInvoice::factory()->create(['invoice_number' => 'INV-2024-002']);
    $invoice3 = ProjectInvoice::factory()->create(['invoice_number' => 'INV-2023-001']);

    $component = Livewire::test(InvoiceList::class)
        ->set('q', '2024');

    $invoices = $component->invoiceList;
    $invoiceIds = $invoices->pluck('id')->toArray();

    expect($invoices)->toHaveCount(2);
    expect($invoiceIds)->toContain($invoice1->id);
    expect($invoiceIds)->toContain($invoice2->id);
    expect($invoiceIds)->not->toContain($invoice3->id);
});

test('search requires minimum 3 characters', function () {
    ProjectInvoice::factory()->count(5)->create();

    $component = Livewire::test(InvoiceList::class)
        ->set('q', 'ab'); // Only 2 characters

    expect($component->invoiceList)->toHaveCount(5); // Should return all
});

test('sorting works correctly', function () {
    $invoice1 = ProjectInvoice::factory()->create(['invoice_number' => 'INV-001']);
    $invoice2 = ProjectInvoice::factory()->create(['invoice_number' => 'INV-002']);

    $component = Livewire::test(InvoiceList::class)
        ->set('sort', 'invoice_number')
        ->set('sortDirection', 'asc');

    $invoices = $component->invoiceList;
    expect($invoices->first()->id)->toBe($invoice1->id);
    expect($invoices->last()->id)->toBe($invoice2->id);
});

test('pagination works with limit', function () {
    ProjectInvoice::factory()->count(15)->create();

    $component = Livewire::test(InvoiceList::class)
        ->set('limit', 5);

    expect($component->invoiceList)->toHaveCount(5);
});

// Note: Page reset is handled automatically by updated() method calling resetPage()

test('can delete invoice', function () {
    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $invoice = ProjectInvoice::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
    ]);

    $token = $invoice->delete_token;

    Livewire::test(InvoiceList::class, ['projectId' => $project->id])
        ->call('delete', $token)
        ->assertDispatched('synapse-confirmed')
        ->assertDispatched('activity-refresh')
        ->assertDispatched('notify');

    expect(ProjectInvoice::find($invoice->id))->toBeNull();
});

test('available years computed property returns distinct years', function () {
    ProjectInvoice::factory()->create(['created_at' => '2023-01-01']);
    ProjectInvoice::factory()->create(['created_at' => '2023-06-01']);
    ProjectInvoice::factory()->create(['created_at' => '2024-01-01']);

    $component = Livewire::test(InvoiceList::class);

    $years = $component->availableYears;
    expect($years)->toHaveCount(2);
    expect($years)->toContain(2023);
    expect($years)->toContain(2024);
});

test('issuers computed property returns document issuers', function () {
    $issuer1 = DocumentIssuer::factory()->create(['name' => 'Issuer A']);
    $issuer2 = DocumentIssuer::factory()->create(['name' => 'Issuer B']);

    $component = Livewire::test(InvoiceList::class);

    $issuers = $component->issuers;
    expect($issuers)->toHaveCount(2);
    expect(collect($issuers)->pluck('label'))->toContain('Issuer A');
    expect(collect($issuers)->pluck('label'))->toContain('Issuer B');
});

test('refreshes project on document upload event', function () {
    $project = Project::factory()->create();

    $component = Livewire::test(InvoiceList::class, ['projectId' => $project->id])
        ->dispatch('document-uploaded');

    expect($component->project)->not->toBeNull();
    expect($component->project->id)->toBe($project->id);
});
