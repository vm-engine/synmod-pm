<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Services\BankAccountService;

uses(RefreshDatabase::class);

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

it('persists a swift code when creating a bank account', function () {
    BankAccount::query()->delete();

    Livewire::test('pm::master.bank-account-form')
        ->set('form.name', 'PT Vision')
        ->set('form.number', '7753997778')
        ->set('form.bank_name', 'BCA (Bank Central Asia)')
        ->set('form.swift_code', 'CENAIDJA')
        ->set('form.bank_logo', UploadedFile::fake()->image('logo.png'))
        ->call('save');

    expect(BankAccount::where('number', '7753997778')->first()->swift_code)->toBe('CENAIDJA');
});

it('returns distinct bank-name suggestions with their swift codes', function () {
    BankAccount::factory()->create(['bank_name' => 'BCA', 'swift_code' => 'CENAIDJA']);
    BankAccount::factory()->create(['bank_name' => 'BCA', 'swift_code' => null]);
    BankAccount::factory()->create(['bank_name' => 'Mandiri', 'swift_code' => 'BMRIIDJA']);

    $suggestions = app(BankAccountService::class)->bankNameSuggestions();

    $names = array_column($suggestions, 'bank_name');
    expect($names)->toContain('BCA', 'Mandiri');
    expect(count($names))->toBe(count(array_unique($names)));

    $bca = collect($suggestions)->firstWhere('bank_name', 'BCA');
    expect($bca['swift_code'])->toBe('CENAIDJA');
});
