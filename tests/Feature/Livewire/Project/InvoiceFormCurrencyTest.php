<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\ProjectInvoiceItem;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
    $this->issuer = DocumentIssuer::factory()->create();
    $this->bank = BankAccount::factory()->create();
});

it('stores an invoice with a chosen currency', function () {
    $project = Project::factory()->create();
    $spk = ProjectDocument::factory()->create(['project_id' => $project->id]);

    Livewire::test('pm::project.invoice-form')
        ->call('createInvoice', $project->id)
        ->set('form.invoice_number', 'INV-TEST-001')
        ->set('form.spk_document_id', $spk->id)
        ->set('form.currency', 'USD')
        ->set('form.recipient', 'ACME')
        ->set('form.address', 'Somewhere')
        ->set('form.issuer_id', $this->issuer->id)
        ->set('form.bank_account_id', $this->bank->id)
        ->call('save');

    expect(ProjectInvoice::where('project_id', $project->id)->first()->currency)->toBe('USD');
});

it('requires free-text spk_number when external SPK is checked', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::project.invoice-form')
        ->call('createInvoice', $project->id)
        ->set('form.is_external_spk', true)
        ->set('form.spk_number', '')
        ->set('form.recipient', 'ACME')
        ->set('form.address', 'X')
        ->call('save')
        ->assertHasErrors(['form.spk_number']);
});

it('stores a free-text spk number and nulls spk_document_id when external', function () {
    $project = Project::factory()->create();

    Livewire::test('pm::project.invoice-form')
        ->call('createInvoice', $project->id)
        ->set('form.invoice_number', 'INV-TEST-002')
        ->set('form.is_external_spk', true)
        ->set('form.spk_number', 'EXT-SPK-001')
        ->set('form.recipient', 'ACME')
        ->set('form.address', 'X')
        ->set('form.issuer_id', $this->issuer->id)
        ->set('form.bank_account_id', $this->bank->id)
        ->call('save');

    $invoice = ProjectInvoice::where('project_id', $project->id)->first();
    expect($invoice->is_external_spk)->toBeTrue();
    expect($invoice->spk_number)->toBe('EXT-SPK-001');
    expect($invoice->spk_document_id)->toBeNull();
});

it('rejects a currency change when the invoice already has items', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR']);
    ProjectInvoiceItem::factory()->create([
        'project_id' => $invoice->project_id,
        'project_invoice_id' => $invoice->id,
        'client_id' => $invoice->client_id,
    ]);

    Livewire::test('pm::project.invoice-form')
        ->call('loadInvoice', $invoice->id)
        ->set('form.currency', 'USD')
        ->call('save')
        ->assertHasErrors(['form.currency']);

    expect($invoice->fresh()->currency)->toBe('IDR');
});
