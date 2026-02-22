<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\BankAccount as ModelsBankAccount;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.bank-account-list')
        ->assertStatus(200);
});

test('loads bank account list', function () {
    ModelsBankAccount::factory()->count(3)->create();

    $component = Livewire::test('pm::master.bank-account-list');

    expect($component->accountList)->toHaveCount(3);
});

test('accounts are ordered by bank name then name', function () {
    $accountC = ModelsBankAccount::factory()->create([
        'bank_name' => 'Bank B',
        'name' => 'Account C',
    ]);

    $accountA = ModelsBankAccount::factory()->create([
        'bank_name' => 'Bank A',
        'name' => 'Account A',
    ]);

    $accountB = ModelsBankAccount::factory()->create([
        'bank_name' => 'Bank A',
        'name' => 'Account B',
    ]);

    $component = Livewire::test('pm::master.bank-account-list');

    $accounts = $component->accountList;
    expect($accounts->first()->id)->toBe($accountA->id);
    expect($accounts->last()->id)->toBe($accountC->id);
});

test('can search accounts by name', function () {
    $account1 = ModelsBankAccount::factory()->create(['name' => 'Main Account']);
    $account2 = ModelsBankAccount::factory()->create(['name' => 'Savings Account']);
    $account3 = ModelsBankAccount::factory()->create(['name' => 'Business Checking']);

    $component = Livewire::test('pm::master.bank-account-list')
        ->set('q', 'Account');

    $accounts = $component->accountList;
    $accountIds = $accounts->pluck('id')->toArray();

    expect($accounts)->toHaveCount(2);
    expect($accountIds)->toContain($account1->id);
    expect($accountIds)->toContain($account2->id);
    expect($accountIds)->not->toContain($account3->id);
});

test('can search accounts by account number', function () {
    $account1 = ModelsBankAccount::factory()->create(['number' => '1234567890']);
    $account2 = ModelsBankAccount::factory()->create(['number' => '0987654321']);

    $component = Livewire::test('pm::master.bank-account-list')
        ->set('q', '1234');

    $accounts = $component->accountList;
    expect($accounts)->toHaveCount(1);
    expect($accounts->first()->id)->toBe($account1->id);
});

test('can search accounts by bank name', function () {
    $account1 = ModelsBankAccount::factory()->create(['bank_name' => 'Bank of America']);
    $account2 = ModelsBankAccount::factory()->create(['bank_name' => 'Chase Bank']);
    $account3 = ModelsBankAccount::factory()->create(['bank_name' => 'Wells Fargo']);

    $component = Livewire::test('pm::master.bank-account-list')
        ->set('q', 'Bank');

    $accounts = $component->accountList;
    expect($accounts)->toHaveCount(2);
});

test('search requires minimum 3 characters', function () {
    ModelsBankAccount::factory()->count(5)->create();

    $component = Livewire::test('pm::master.bank-account-list')
        ->set('q', 'ab');

    expect($component->accountList)->toHaveCount(5);
});

test('pagination works with limit', function () {
    ModelsBankAccount::factory()->count(15)->create();

    $component = Livewire::test('pm::master.bank-account-list')
        ->set('limit', 5);

    expect($component->accountList)->toHaveCount(5);
});

// Note: Page reset is handled automatically by updated() method calling resetPage()

test('can delete bank account', function () {
    $account = ModelsBankAccount::factory()->create([
        'name' => 'Test Account',
        'bank_logo' => null,
    ]);

    $token = $account->delete_token;

    Livewire::test('pm::master.bank-account-list')
        ->call('delete', $token)
        ->assertDispatched('synapse-confirmed')
        ->assertDispatched('notify');

    expect(ModelsBankAccount::find($account->id))->toBeNull();
});

test('deletes bank logo when account is deleted', function () {
    Storage::disk('public')->put('bank-logos/test.png', 'logo image');

    $account = ModelsBankAccount::factory()->create([
        'bank_logo' => 'bank-logos/test.png',
    ]);

    $token = $account->delete_token;

    Livewire::test('pm::master.bank-account-list')
        ->call('delete', $token);

    expect(Storage::disk('public')->exists('bank-logos/test.png'))->toBeFalse();
});

test('handles deletion when logo does not exist', function () {
    $account = ModelsBankAccount::factory()->create([
        'bank_logo' => 'bank-logos/non-existent.png',
    ]);

    $token = $account->delete_token;

    Livewire::test('pm::master.bank-account-list')
        ->call('delete', $token)
        ->assertDispatched('notify');

    expect(ModelsBankAccount::find($account->id))->toBeNull();
});

test('handles deletion with invalid token', function () {
    ModelsBankAccount::factory()->create();

    Livewire::test('pm::master.bank-account-list')
        ->call('delete', 'invalid-token')
        ->assertDispatched('notify');
});

// Note: Cannot test non-existent account deletion without a valid token from an actual model instance

test('sets active menu', function () {
    Livewire::test('pm::master.bank-account-list');

    expect(synav()->getActiveMenu())->toBe('pm.master.bank_account');
});

test('search is persisted in URL', function () {
    Livewire::test('pm::master.bank-account-list')
        ->set('q', 'test')
        ->assertSet('q', 'test');
});
