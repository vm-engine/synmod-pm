<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Component;
use Livewire\Livewire;
use VmEngine\Pm\Form\InvoiceFormObject;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\Signature;
use VmEngine\SynAuth\Models\Role;
use VmEngine\SynAuth\Models\RolePermission;
use VmEngine\SynAuth\Models\User;

uses(RefreshDatabase::class);

/**
 * Create a user that passes `pm.config.update` gate checks.
 * Uses the synapps-auth RBAC: role + RolePermission on module=pm, feature=config, can_update=true.
 */
function makePmConfigUpdater(): User
{
    $role = Role::factory()->create(['slug' => 'pm-config-editor']);
    $user = User::factory()->create();
    $user->roles()->attach($role);
    RolePermission::factory()
        ->forRole($role)
        ->forModule('pm', 'config')
        ->create([
            'can_create' => false,
            'can_read' => true,
            'can_update' => true,
            'can_delete' => false,
        ]);

    return $user;
}

function makePlainUser(): User
{
    return User::factory()->create();
}

beforeEach(function () {
    $this->actingAs(makePmConfigUpdater());
});

it('preloads general configs from dbconfig on mount', function () {
    dbconf([
        'pm.invoice.vat_percentage' => 12.5,
        'pm.invoice.include_vat_default' => false,
        'pm.invoice.dpp_numerator' => 12,
        'pm.invoice.dpp_denominator' => 13,
        'pm.invoice.payment_due_days' => 30,
    ]);

    Livewire::test('pm::config-page')
        ->assertSet('generalConfigs.vat_percentage', 12.5)
        ->assertSet('generalConfigs.include_vat_default', false)
        ->assertSet('generalConfigs.dpp_numerator', 12)
        ->assertSet('generalConfigs.dpp_denominator', 13)
        ->assertSet('generalConfigs.payment_due_days', 30);
});

it('falls back to literal defaults when config rows are missing', function () {
    Livewire::test('pm::config-page')
        ->assertSet('generalConfigs.vat_percentage', 11.0)
        ->assertSet('generalConfigs.include_vat_default', true)
        ->assertSet('generalConfigs.dpp_numerator', 11)
        ->assertSet('generalConfigs.dpp_denominator', 12)
        ->assertSet('generalConfigs.payment_due_days', 14)
        ->assertSet('generalConfigs.default_issuer_id', null)
        ->assertSet('generalConfigs.default_bank_account_id', null)
        ->assertSet('generalConfigs.default_signature_id', null);
});

it('saves all general configs to dbconfig', function () {
    $issuer = DocumentIssuer::factory()->create();
    $bank = BankAccount::factory()->create();
    $signature = Signature::factory()->create();

    Livewire::test('pm::config-page')
        ->set('generalConfigs.vat_percentage', 12.0)
        ->set('generalConfigs.include_vat_default', false)
        ->set('generalConfigs.dpp_numerator', 12)
        ->set('generalConfigs.dpp_denominator', 13)
        ->set('generalConfigs.payment_due_days', 30)
        ->set('generalConfigs.default_issuer_id', $issuer->id)
        ->set('generalConfigs.default_bank_account_id', $bank->id)
        ->set('generalConfigs.default_signature_id', $signature->id)
        ->call('saveGeneral')
        ->assertHasNoErrors();

    expect((float) dbconf('!pm.invoice.vat_percentage'))->toBe(12.0);
    expect((bool) dbconf('!pm.invoice.include_vat_default'))->toBe(false);
    expect((int) dbconf('!pm.invoice.dpp_numerator'))->toBe(12);
    expect((int) dbconf('!pm.invoice.dpp_denominator'))->toBe(13);
    expect((int) dbconf('!pm.invoice.payment_due_days'))->toBe(30);
    expect((int) dbconf('!pm.invoice.default_issuer_id'))->toBe($issuer->id);
    expect((int) dbconf('!pm.invoice.default_bank_account_id'))->toBe($bank->id);
    expect((int) dbconf('!pm.invoice.default_signature_id'))->toBe($signature->id);
});

it('rejects save when user lacks pm.config.update permission', function () {
    $this->actingAs(makePlainUser());

    Livewire::test('pm::config-page')
        ->set('generalConfigs.vat_percentage', 12.0)
        ->call('saveGeneral');

    // Contract: the permission check short-circuits before dbconf writes.
    // Flash messaging is UX (not asserted here — Livewire single-request tests
    // don't surface flash data the same way a full HTTP request does).
    expect((float) dbconf('!pm.invoice.vat_percentage', 11.0))->toBe(11.0);
});

it('validates vat_percentage range', function () {
    Livewire::test('pm::config-page')
        ->set('generalConfigs.vat_percentage', -1)
        ->call('saveGeneral')
        ->assertHasErrors(['generalConfigs.vat_percentage' => 'min']);

    Livewire::test('pm::config-page')
        ->set('generalConfigs.vat_percentage', 101)
        ->call('saveGeneral')
        ->assertHasErrors(['generalConfigs.vat_percentage' => 'max']);
});

it('validates dpp denominator is at least 1', function () {
    Livewire::test('pm::config-page')
        ->set('generalConfigs.dpp_denominator', 0)
        ->call('saveGeneral')
        ->assertHasErrors(['generalConfigs.dpp_denominator' => 'min']);
});

it('validates issuer id exists', function () {
    Livewire::test('pm::config-page')
        ->set('generalConfigs.default_issuer_id', 99999)
        ->call('saveGeneral')
        ->assertHasErrors(['generalConfigs.default_issuer_id' => 'exists']);
});

/**
 * Minimal Component stand-in for constructing InvoiceFormObject outside Livewire's
 * test runner. setProject() doesn't touch the parent component, so this is safe.
 */
function makeFormHost(): Component
{
    return new class extends Component
    {
        public function render() {}
    };
}

it('applies dbconfig defaults when setProject is called for a new invoice', function () {
    $issuer = DocumentIssuer::factory()->create();
    $bank = BankAccount::factory()->create();
    $signature = Signature::factory()->create();

    dbconf([
        'pm.invoice.payment_due_days' => 30,
        'pm.invoice.default_issuer_id' => $issuer->id,
        'pm.invoice.default_bank_account_id' => $bank->id,
        'pm.invoice.default_signature_id' => $signature->id,
    ]);

    $project = Project::factory()->create();

    $form = new InvoiceFormObject(makeFormHost(), 'form');
    $form->setProject($project);

    expect($form->due_date)->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($form->issuer_id)->toBe($issuer->id);
    expect($form->bank_account_id)->toBe($bank->id);
    expect($form->signature_id)->toBe($signature->id);
});

it('falls back to literal payment due days when config is missing', function () {
    $project = Project::factory()->create();

    $form = new InvoiceFormObject(makeFormHost(), 'form');
    $form->setProject($project);

    expect($form->due_date)->toBe(now()->addDays(14)->format('Y-m-d'));
    expect($form->issuer_id)->toBeNull();
    expect($form->bank_account_id)->toBeNull();
    expect($form->signature_id)->toBeNull();
});

it('casts string-valued dbconfig IDs to int in setProject (DB-roundtrip path)', function () {
    // dbconf is a singleton with in-process caching — earlier tests may have
    // cached null for these keys. Rebind to force a fresh DB read below.
    app()->forgetInstance('dbconf');

    // Seed configs directly — bypasses DbConfig::set cache, so the subsequent
    // dbconf() read inside setProject() goes through a real DB roundtrip and
    // returns strings (the production scenario that caused the TypeError).
    $issuer = DocumentIssuer::factory()->create();
    $bank = BankAccount::factory()->create();
    $signature = Signature::factory()->create();

    // dbconf splits on the first '.' — 'pm.invoice.default_issuer_id' becomes
    // group='pm', key='invoice.default_issuer_id'.
    \VmEngine\Synapse\Models\Config::create([
        'group' => 'pm',
        'key' => 'invoice.default_issuer_id',
        'value' => (string) $issuer->id,
    ]);
    \VmEngine\Synapse\Models\Config::create([
        'group' => 'pm',
        'key' => 'invoice.default_bank_account_id',
        'value' => (string) $bank->id,
    ]);
    \VmEngine\Synapse\Models\Config::create([
        'group' => 'pm',
        'key' => 'invoice.default_signature_id',
        'value' => (string) $signature->id,
    ]);

    $project = Project::factory()->create();
    $form = new InvoiceFormObject(makeFormHost(), 'form');
    $form->setProject($project);

    expect($form->issuer_id)->toBe($issuer->id);
    expect($form->bank_account_id)->toBe($bank->id);
    expect($form->signature_id)->toBe($signature->id);
});

it('uses dbconfig vat_percentage and include_vat_default on new invoice mount', function () {
    dbconf([
        'pm.invoice.vat_percentage' => 12.0,
        'pm.invoice.include_vat_default' => false,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->assertSet('vatPercentage', 12.0)
        ->assertSet('includeVat', false);
});

it('keeps persisted invoice vat_percentage after loadInvoice', function () {
    dbconf(['pm.invoice.vat_percentage' => 12.0]);

    $invoice = \VmEngine\Pm\Models\ProjectInvoice::factory()->create([
        'vat_percentage' => 11.00,
        'include_vat' => true,
        'archived' => false,
    ]);

    Livewire::test('pm::project.invoice-detail')
        ->call('loadInvoice', $invoice->id)
        ->assertSet('vatPercentage', 11.0)
        ->assertSet('includeVat', true);
});

it('computes dpp using dbconfig numerator/denominator', function () {
    dbconf([
        'pm.invoice.dpp_numerator' => 12,
        'pm.invoice.dpp_denominator' => 13,
    ]);

    $instance = Livewire::test('pm::project.invoice-detail')->instance();
    $method = new ReflectionMethod($instance, 'dppRatio');
    $method->setAccessible(true);

    expect($method->invoke($instance))->toEqualWithDelta(12 / 13, 0.0001);
});

it('guards against dpp_denominator zero by falling back to 12', function () {
    dbconf([
        'pm.invoice.dpp_numerator' => 11,
        'pm.invoice.dpp_denominator' => 0,
    ]);

    $instance = Livewire::test('pm::project.invoice-detail')->instance();
    $method = new ReflectionMethod($instance, 'dppRatio');
    $method->setAccessible(true);

    expect($method->invoke($instance))->toEqualWithDelta(11 / 12, 0.0001);
});

it('exposes a dynamic dppLabel computed property', function () {
    dbconf([
        'pm.invoice.dpp_numerator' => 12,
        'pm.invoice.dpp_denominator' => 13,
    ]);

    $instance = Livewire::test('pm::project.invoice-detail')->instance();

    expect($instance->dppLabel)->toBe('(12/13)');
});

it('factory uses dbconfig vat_percentage when set', function () {
    dbconf(['pm.invoice.vat_percentage' => 12.5]);

    $invoice = \VmEngine\Pm\Models\ProjectInvoice::factory()->create([
        'include_vat' => true,
    ]);

    expect((float) $invoice->vat_percentage)->toBe(12.5);
});

it('factory falls back to 11 when dbconfig is missing', function () {
    $invoice = \VmEngine\Pm\Models\ProjectInvoice::factory()->create([
        'include_vat' => true,
    ]);

    expect((float) $invoice->vat_percentage)->toBe(11.0);
});

it('InvoiceService::create applies dbconfig VAT defaults when caller omits them', function () {
    dbconf([
        'pm.invoice.vat_percentage' => 12.0,
        'pm.invoice.include_vat_default' => false,
    ]);

    $this->actingAs(\VmEngine\SynAuth\Models\User::factory()->create());

    // Build a valid invoice payload via the factory, then strip VAT fields so
    // create() must consult dbconfig for them.
    $payload = \VmEngine\Pm\Models\ProjectInvoice::factory()->make()->toArray();
    unset($payload['vat_percentage'], $payload['include_vat'], $payload['vat_amount']);

    $project = Project::find($payload['project_id']) ?? Project::factory()->create();
    $payload['project_id'] = $project->id;

    $invoice = app(\VmEngine\Pm\Services\InvoiceService::class)->create($project, $payload);

    expect((float) $invoice->vat_percentage)->toBe(12.0)
        ->and((bool) $invoice->include_vat)->toBeFalse();
});

it('InvoiceService::create respects explicit VAT in caller data over dbconfig', function () {
    dbconf(['pm.invoice.vat_percentage' => 12.0]);

    $this->actingAs(\VmEngine\SynAuth\Models\User::factory()->create());

    $payload = \VmEngine\Pm\Models\ProjectInvoice::factory()->make()->toArray();
    $payload['vat_percentage'] = 15.0;
    $payload['include_vat'] = true;

    $project = Project::find($payload['project_id']) ?? Project::factory()->create();
    $payload['project_id'] = $project->id;

    $invoice = app(\VmEngine\Pm\Services\InvoiceService::class)->create($project, $payload);

    expect((float) $invoice->vat_percentage)->toBe(15.0)
        ->and((bool) $invoice->include_vat)->toBeTrue();
});
