<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\ProjectInvoice;
use VmEngine\Pm\Models\ProjectInvoiceItem;
use VmEngine\Pm\Services\InvoicePdfService;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware();
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

it('generates both language documents and downloads the requested one', function () {
    $bank = BankAccount::factory()->create(['bank_name' => 'BCA', 'swift_code' => 'CENAIDJA']);
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR', 'bank_account_id' => $bank->id]);
    ProjectInvoiceItem::factory()->create([
        'project_id' => $invoice->project_id,
        'project_invoice_id' => $invoice->id,
        'client_id' => $invoice->client_id,
    ]);

    (new InvoicePdfService)->generate($invoice->fresh(), $this->user->id);
    $invoice->refresh();

    expect($invoice->invoice_document_id)->not->toBeNull();
    expect($invoice->invoice_document_en_id)->not->toBeNull();

    get(route('backend.pm.invoice.download', ['id' => $invoice->id, 'lang' => 'en']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('renders the invoice print view in Indonesian by default', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR']);

    get(route('backend.pm.invoice.print', ['id' => $invoice->id]))
        ->assertOk()
        ->assertSee('No Faktur', false);
});

it('renders the invoice print view in English when requested', function () {
    $invoice = ProjectInvoice::factory()->create(['currency' => 'IDR']);

    get(route('backend.pm.invoice.print', ['id' => $invoice->id, 'lang' => 'en']))
        ->assertOk()
        ->assertSee('No Invoice', false);
});
