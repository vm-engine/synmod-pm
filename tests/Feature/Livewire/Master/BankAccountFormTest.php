<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Livewire\Master\BankAccountForm;
use VmEngine\Pm\Models\BankAccount;

beforeEach(function () {
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test(BankAccountForm::class)
        ->assertStatus(200);
});

test('can create new bank account', function () {
    $logo = UploadedFile::fake()->image('bank-logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Main Account')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank of America')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-bank-account-form')
        ->assertDispatched('bank-account-refresh');

    expect(BankAccount::where('name', 'Main Account')->exists())->toBeTrue();

    $account = BankAccount::where('name', 'Main Account')->first();
    expect($account->number)->toBe('1234567890');
    expect($account->bank_name)->toBe('Bank of America');
    expect($account->bank_logo)->not->toBeNull();
    expect($account->created_by)->toBe($this->user->id);
});

test('stores bank logo file', function () {
    $logo = UploadedFile::fake()->image('bank-logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Main Account')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank of America')
        ->set('form.bank_logo', $logo)
        ->call('save');

    $account = BankAccount::where('name', 'Main Account')->first();

    expect(Storage::disk('public')->exists($account->bank_logo))->toBeTrue();
});

test('can update existing bank account', function () {
    $account = BankAccount::factory()->create([
        'name' => 'Old Account',
        'number' => '0000000000',
        'bank_name' => 'Old Bank',
    ]);

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->set('form.name', 'Updated Account')
        ->set('form.number', '1111111111')
        ->set('form.bank_name', 'Updated Bank')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-bank-account-form');

    expect($account->fresh()->name)->toBe('Updated Account');
    expect($account->fresh()->number)->toBe('1111111111');
    expect($account->fresh()->bank_name)->toBe('Updated Bank');
    expect($account->fresh()->updated_by)->toBe($this->user->id);
});

test('can update bank account with new logo', function () {
    Storage::disk('public')->put('bank_accounts/logos/old.png', 'old content');

    $account = BankAccount::factory()->create([
        'name' => 'Main Account',
        'bank_logo' => 'bank_accounts/logos/old.png',
    ]);

    $newLogo = UploadedFile::fake()->image('new-logo.png');

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->set('form.name', 'Main Account')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $newLogo)
        ->call('save')
        ->assertHasNoErrors();

    // Old logo should be deleted
    expect(Storage::disk('public')->exists('bank_accounts/logos/old.png'))->toBeFalse();

    // New logo should exist
    $account->refresh();
    expect(Storage::disk('public')->exists($account->bank_logo))->toBeTrue();
});

test('validates required name', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', '')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.name' => 'required']);
});

test('validates required number', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.number' => 'required']);
});

test('validates required bank name', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', '')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.bank_name' => 'required']);
});

test('validates required logo for new account', function () {
    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', null)
        ->call('save')
        ->assertHasErrors(['form.bank_logo' => 'required']);
});

test('logo is optional when updating account', function () {
    $account = BankAccount::factory()->create([
        'name' => 'Account Name',
        'bank_logo' => 'bank_accounts/logos/existing.png',
    ]);

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->set('form.name', 'Updated Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', null)
        ->call('save')
        ->assertHasNoErrors();

    expect($account->fresh()->name)->toBe('Updated Name');
    expect($account->fresh()->bank_logo)->toBe('bank_accounts/logos/existing.png');
});

test('validates logo mime types', function () {
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $file)
        ->call('save')
        ->assertHasErrors(['form.bank_logo' => 'mimes']);
});

test('validates logo max size', function () {
    $file = UploadedFile::fake()->create('large-logo.png', 6000); // 6MB

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $file)
        ->call('save')
        ->assertHasErrors(['form.bank_logo' => 'max']);
});

test('validates name max length', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', str_repeat('a', 256))
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.name' => 'max']);
});

test('validates number max length', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', str_repeat('1', 256))
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.number' => 'max']);
});

test('validates bank name max length', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', str_repeat('a', 256))
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasErrors(['form.bank_name' => 'max']);
});

test('loads bank account data correctly', function () {
    $account = BankAccount::factory()->create([
        'name' => 'Main Account',
        'number' => '1234567890',
        'bank_name' => 'Bank of America',
        'bank_logo' => 'bank_accounts/logos/logo.png',
    ]);

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->assertSet('form.name', 'Main Account')
        ->assertSet('form.number', '1234567890')
        ->assertSet('form.bank_name', 'Bank of America')
        ->assertSet('form.existing_bank_logo', 'bank_accounts/logos/logo.png');
});

test('handles loading non-existent account', function () {
    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: 99999)
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-bank-account-form');
});

test('resets form on close modal event', function () {
    $account = BankAccount::factory()->create();

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->assertSet('form.name', $account->name)
        ->dispatch('close-modal-bank-account-form')
        ->assertSet('form.name', '');
});

test('resets form on reset event', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->dispatch('reset-bank-account-form')
        ->assertSet('form.name', '')
        ->assertSet('form.number', '')
        ->assertSet('form.bank_name', '')
        ->assertSet('form.bank_logo', null);
});

test('dispatches dragndrop clear event on save', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertDispatched('dragndrop-clear-input');
});

test('dispatches dragndrop clear event on reset', function () {
    Livewire::test(BankAccountForm::class)
        ->dispatch('reset-bank-account-form')
        ->assertDispatched('dragndrop-clear-input');
});

test('accepts jpeg image format', function () {
    $logo = UploadedFile::fake()->image('logo.jpg');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasNoErrors();
});

test('accepts png image format', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasNoErrors();
});

test('accepts webp image format', function () {
    $logo = UploadedFile::fake()->create('logo.webp', 100, 'image/webp');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertHasNoErrors();
});

test('shows success message for create', function () {
    $logo = UploadedFile::fake()->image('logo.png');

    Livewire::test(BankAccountForm::class)
        ->set('form.name', 'Account Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->set('form.bank_logo', $logo)
        ->call('save')
        ->assertDispatched('notify');
});

test('shows success message for update', function () {
    $account = BankAccount::factory()->create();

    Livewire::test(BankAccountForm::class)
        ->dispatch('load-bank-account', id: $account->id)
        ->set('form.name', 'Updated Name')
        ->set('form.number', '1234567890')
        ->set('form.bank_name', 'Bank Name')
        ->call('save')
        ->assertDispatched('notify');
});
