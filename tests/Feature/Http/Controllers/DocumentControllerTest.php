<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectDocument;
use VmEngine\SynAuth\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');

    $UserModel = config('auth.providers.users.model');
    $this->user = $UserModel::factory()->create();

    // Create dev role with full access (use firstOrCreate to avoid duplicates)
    $devRole = Role::firstOrCreate(
        ['slug' => 'dev'],
        ['name' => 'Developer', 'level' => 0]
    );
    $this->user->roles()->attach($devRole);

    // Skip ACL middleware for simpler testing
    $this->withoutMiddleware();
});

test('can download document with proper authorization', function () {
    $this->actingAs($this->user);

    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'documents/test-doc.pdf',
        'file_info' => ['name' => 'test-doc.pdf'],
    ]);

    Storage::disk('local')->put('documents/test-doc.pdf', 'fake document content');

    $response = $this->get(route('backend.pm.document.download', $document->id));

    $response->assertStatus(200);
    $response->assertDownload('test-doc.pdf');
});

test('can print document inline', function () {
    $this->actingAs($this->user);

    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'documents/test-doc.pdf',
    ]);

    Storage::disk('local')->put('documents/test-doc.pdf', 'fake document content');

    $response = $this->get(route('backend.pm.document.print', $document->id));

    $response->assertStatus(200);
});

test('returns 404 when document does not exist', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('backend.pm.document.download', 99999));

    $response->assertStatus(404);
});

test('returns 404 when file does not exist on disk', function () {
    $this->actingAs($this->user);

    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'documents/non-existent.pdf',
    ]);

    $response = $this->get(route('backend.pm.document.download', $document->id));

    $response->assertStatus(404);
});

test('returns correct mime type for document', function () {
    $this->actingAs($this->user);

    $client = Client::factory()->create();
    $project = Project::factory()->create(['client_id' => $client->id]);
    $document = ProjectDocument::factory()->create([
        'project_id' => $project->id,
        'client_id' => $client->id,
        'file_path' => 'documents/test-doc.pdf',
        'file_info' => [
            'name' => 'test-doc.pdf',
            'mime' => 'application/pdf',
        ],
    ]);

    Storage::disk('local')->put('documents/test-doc.pdf', 'fake document content');

    $response = $this->get(route('backend.pm.document.download', $document->id));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});
