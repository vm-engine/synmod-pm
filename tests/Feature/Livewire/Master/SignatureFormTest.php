<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use VmEngine\Pm\Models\Signature;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    Livewire::test('pm::master.signature-form')
        ->assertStatus(200);
});

test('can create new signature', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-signature-form')
        ->assertDispatched('signature-refresh');

    expect(Signature::where('name', 'John Doe')->exists())->toBeTrue();

    $signature = Signature::where('name', 'John Doe')->first();
    expect($signature->position)->toBe('Director');
    expect($signature->file)->not->toBeNull();
    expect($signature->created_by)->toBe($this->user->id);
});

test('stores signature image file', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save');

    $signature = Signature::where('name', 'John Doe')->first();

    expect(Storage::disk('public')->exists($signature->file))->toBeTrue();
});

test('can update existing signature', function () {
    $signature = Signature::factory()->create([
        'name' => 'Old Name',
        'position' => 'Old Position',
    ]);

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->set('form.name', 'Updated Name')
        ->set('form.position', 'Updated Position')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-signature-form');

    expect($signature->fresh()->name)->toBe('Updated Name');
    expect($signature->fresh()->position)->toBe('Updated Position');
    expect($signature->fresh()->updated_by)->toBe($this->user->id);
});

test('can update signature with new file', function () {
    Storage::disk('public')->put('signatures/old.png', 'old content');

    $signature = Signature::factory()->create([
        'name' => 'John Doe',
        'file' => 'signatures/old.png',
    ]);

    $newFile = UploadedFile::fake()->image('new-signature.png');

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $newFile)
        ->call('save')
        ->assertHasNoErrors();

    // Old file should be deleted
    expect(Storage::disk('public')->exists('signatures/old.png'))->toBeFalse();

    // New file should exist
    $signature->refresh();
    expect(Storage::disk('public')->exists($signature->file))->toBeTrue();
});

test('validates required name', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', '')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.name' => 'required']);
});

test('validates required position', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', '')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.position' => 'required']);
});

test('validates required file for new signature', function () {
    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', null)
        ->call('save')
        ->assertHasErrors(['form.file' => 'required']);
});

test('file is optional when updating signature', function () {
    $signature = Signature::factory()->create([
        'name' => 'John Doe',
        'file' => 'signatures/existing.png',
    ]);

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->set('form.name', 'Updated Name')
        ->set('form.position', 'Updated Position')
        ->set('form.file', null)
        ->call('save')
        ->assertHasNoErrors();

    expect($signature->fresh()->name)->toBe('Updated Name');
    expect($signature->fresh()->file)->toBe('signatures/existing.png'); // File unchanged
});

test('validates file mime types', function () {
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.file' => 'mimes']);
});

test('validates file max size', function () {
    $file = UploadedFile::fake()->create('large-image.png', 6000); // 6MB

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.file' => 'max']);
});

test('validates name max length', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', str_repeat('a', 256))
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.name' => 'max']);
});

test('validates position max length', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', str_repeat('a', 256))
        ->set('form.file', $file)
        ->call('save')
        ->assertHasErrors(['form.position' => 'max']);
});

test('loads signature data correctly', function () {
    $signature = Signature::factory()->create([
        'name' => 'John Doe',
        'position' => 'Director',
        'file' => 'signatures/test.png',
    ]);

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->assertSet('form.name', 'John Doe')
        ->assertSet('form.position', 'Director')
        ->assertSet('form.existing_file', 'signatures/test.png');
});

test('handles loading non-existent signature', function () {
    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: 99999)
        ->assertDispatched('notify')
        ->assertDispatched('close-modal-signature-form');
});

test('resets form on close modal event', function () {
    $signature = Signature::factory()->create();

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->assertSet('form.name', $signature->name)
        ->dispatch('close-modal-signature-form')
        ->assertSet('form.name', '');
});

test('resets form on reset event', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->dispatch('reset-signature-form')
        ->assertSet('form.name', '')
        ->assertSet('form.position', '')
        ->assertSet('form.file', null);
});

test('dispatches dragndrop clear event on save', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertDispatched('dragndrop-clear-input');
});

test('dispatches dragndrop clear event on reset', function () {
    Livewire::test('pm::master.signature-form')
        ->dispatch('reset-signature-form')
        ->assertDispatched('dragndrop-clear-input');
});

test('accepts jpeg image format', function () {
    $file = UploadedFile::fake()->image('signature.jpg');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasNoErrors();
});

test('accepts png image format', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasNoErrors();
});

test('accepts webp image format', function () {
    $file = UploadedFile::fake()->create('signature.webp', 100, 'image/webp');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertHasNoErrors();
});

test('shows success message for create', function () {
    $file = UploadedFile::fake()->image('signature.png');

    Livewire::test('pm::master.signature-form')
        ->set('form.name', 'John Doe')
        ->set('form.position', 'Director')
        ->set('form.file', $file)
        ->call('save')
        ->assertDispatched('notify');
});

test('shows success message for update', function () {
    $signature = Signature::factory()->create();

    Livewire::test('pm::master.signature-form')
        ->dispatch('load-signature', id: $signature->id)
        ->set('form.name', 'Updated Name')
        ->set('form.position', 'Updated Position')
        ->call('save')
        ->assertDispatched('notify');
});
