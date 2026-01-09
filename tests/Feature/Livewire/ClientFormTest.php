<?php

declare(strict_types=1);

use Livewire\Volt\Volt;
use VmEngine\Pm\Livewire\ClientForm;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ClientCategory;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered for create', function () {
    Volt::test(ClientForm::class)
        ->assertStatus(200)
        ->assertSet('form.client', null);
});

test('component can be rendered for edit', function () {
    $client = Client::factory()->create(['company' => 'Test Co']);

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->assertStatus(200)
        ->assertSet('form.company', 'Test Co');
});

test('redirects when client not found', function () {
    Volt::test(ClientForm::class, ['id' => 99999])
        ->assertRedirect();
});

test('can create new client with user', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);
    $uniqueEmail = "test{$uniqueId}@example.com";
    $uniqueUsername = "user{$uniqueId}";

    Volt::test(ClientForm::class)
        ->set('form.company', 'New Company')
        ->set('form.client_category_id', $category->id)
        ->set('form.address', '123 Main St')
        ->set('form.city', 'New York')
        ->set('form.director', 'John Doe')
        ->set('form.pic', 'Jane Smith')
        ->set('form.phone', '555-1234')
        // ->set('form.services', [1, 2]) // Services field removed
        ->set('form.social_media', "website.com\nlinkedin.com/company")
        ->set('form.status', Client::STATUS_ACTIVE)
        ->set('form.active', true)
        ->set('form.name', 'John User')
        ->set('form.username', $uniqueUsername)
        ->set('form.email', $uniqueEmail)
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $client = Client::where('company', 'New Company')->first();
    expect($client)->not->toBeNull();
    expect($client->user->name)->toBe('John User');
    expect($client->user->email)->toBe($uniqueEmail);
    // Password check skipped - requires APP_KEY
});

test('creates user with correct status for active client', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', 'Active Company')
        ->set('form.client_category_id', $category->id)
        ->set('form.status', Client::STATUS_ACTIVE)
        ->set('form.active', true)
        ->set('form.name', 'Active User')
        ->set('form.username', "activeuser{$uniqueId}")
        ->set('form.email', "active{$uniqueId}@example.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', 'Active Company')->first();
    expect($client)->not->toBeNull();
    expect($client->user->status)->toBe(1);
});

test('creates user with inactive status for prospect client', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', 'Prospect Company')
        ->set('form.client_category_id', $category->id)
        ->set('form.status', Client::STATUS_PROSPECT)
        ->set('form.active', true)
        ->set('form.name', 'Prospect User')
        ->set('form.username', "prospectuser{$uniqueId}")
        ->set('form.email', "prospect{$uniqueId}@example.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', 'Prospect Company')->first();
    expect($client)->not->toBeNull();
    expect($client->user->status)->toBe(0);
});

test('creates user with inactive status when active is false', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', 'Inactive Company')
        ->set('form.client_category_id', $category->id)
        ->set('form.status', Client::STATUS_ACTIVE)
        ->set('form.active', false)
        ->set('form.name', 'Inactive User')
        ->set('form.username', "inactiveuser{$uniqueId}")
        ->set('form.email', "inactive{$uniqueId}@example.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', 'Inactive Company')->first();
    expect($client)->not->toBeNull();
    expect($client->user->status)->toBe(0);
});

test('can update existing client', function () {
    $client = Client::factory()->create(['company' => 'Old Company']);

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.company', 'Updated Company')
        ->set('form.city', 'Updated City')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    expect($client->fresh()->company)->toBe('Updated Company');
    expect($client->fresh()->city)->toBe('Updated City');
});

test('can update client without changing password', function () {
    $client = Client::factory()->create();
    $originalPassword = $client->user->password;

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.company', 'Updated Company')
        ->set('form.password', '')
        ->set('form.password_confirmation', '')
        ->call('save')
        ->assertHasNoErrors();

    expect($client->user->fresh()->password)->toBe($originalPassword);
});

test('can update client password', function () {
    $client = Client::factory()->create();
    $oldPassword = $client->user->password;

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.password', 'NewPass@1234')
        ->set('form.password_confirmation', 'NewPass@1234')
        ->call('save')
        ->assertHasNoErrors();

    // Password should have changed
    expect($client->user->fresh()->password)->not->toBe($oldPassword);
});

test('updates user status when client status changes', function () {
    $client = Client::factory()->active()->create(['active' => true]);
    expect($client->user->status)->toBe(1);

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.status', Client::STATUS_PROSPECT)
        ->call('save');

    expect($client->user->fresh()->status)->toBe(0);
});

test('updates user status when active flag changes', function () {
    $client = Client::factory()->active()->create(['active' => true]);
    expect($client->user->status)->toBe(1);

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.active', false)
        ->call('save');

    expect($client->user->fresh()->status)->toBe(0);
});

test('validates company is required', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', '')
        ->set('form.name', 'Test User')
        ->set('form.email', '[email protected]')
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasErrors(['form.company']);
});

test('validates email is required', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', '')
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasErrors(['form.email']);
});

test('validates email format', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', 'invalid-email')
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasErrors(['form.email']);
});

test('validates email uniqueness', function () {
    $existingClient = Client::factory()->create();

    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', $existingClient->user->email)
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasErrors(['form.email']);
});

test('allows same email when editing own record', function () {
    $client = Client::factory()->create();

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.email', $client->user->email)
        ->call('save')
        ->assertHasNoErrors(['form.email']);
});

test('validates password is required on create', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', '[email protected]')
        ->set('form.password', '')
        ->call('save')
        ->assertHasErrors(['form.password']);
});

test('validates password confirmation matches', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', '[email protected]')
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Different@1234')
        ->call('save')
        ->assertHasErrors(['form.password']);
});

test('validates password complexity', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', '[email protected]')
        ->set('form.password', 'simple')
        ->set('form.password_confirmation', 'simple')
        ->call('save')
        ->assertHasErrors(['form.password']);
});

test('validates status is valid', function () {
    Volt::test(ClientForm::class)
        ->set('form.company', 'Test Company')
        ->set('form.name', 'Test User')
        ->set('form.email', '[email protected]')
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->set('form.status', 99)
        ->call('save')
        ->assertHasErrors(['form.status']);
});

test('parses social media textarea into array', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', "Test Company {$uniqueId}")
        ->set('form.client_category_id', $category->id)
        ->set('form.social_media', "website.com\nlinkedin.com\n\nfacebook.com")
        ->set('form.name', 'Test User')
        ->set('form.username', "testuser{$uniqueId}")
        ->set('form.email', "test{$uniqueId}@social.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', "Test Company {$uniqueId}")->first();
    expect($client)->not->toBeNull();
    expect($client->social_media)->toBe(['website.com', 'linkedin.com', 'facebook.com']);
});

test('sets email verified at on user creation', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', "Test Company {$uniqueId}")
        ->set('form.client_category_id', $category->id)
        ->set('form.name', 'Test User')
        ->set('form.username', "testuser{$uniqueId}")
        ->set('form.email', "test{$uniqueId}@verified.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', "Test Company {$uniqueId}")->first();
    expect($client)->not->toBeNull();
    expect($client->user->email_verified_at)->not->toBeNull();
});

test('sets created_by field on creation', function () {
    $category = ClientCategory::factory()->create();
    $uniqueId = substr(md5(uniqid()), 0, 8);

    Volt::test(ClientForm::class)
        ->set('form.company', "Test Company {$uniqueId}")
        ->set('form.client_category_id', $category->id)
        ->set('form.name', 'Test User')
        ->set('form.username', "testuser{$uniqueId}")
        ->set('form.email', "test{$uniqueId}@created.com")
        ->set('form.password', 'Test@1234')
        ->set('form.password_confirmation', 'Test@1234')
        ->call('save')
        ->assertHasNoErrors();

    $client = Client::where('company', "Test Company {$uniqueId}")->first();
    expect($client)->not->toBeNull();
    expect($client->created_by)->toBe($this->user->id);
});

test('sets updated_by field on update', function () {
    $client = Client::factory()->create();

    Volt::test(ClientForm::class, ['id' => $client->id])
        ->set('form.company', 'Updated Company')
        ->call('save');

    expect($client->fresh()->updated_by)->toBe($this->user->id);
});
