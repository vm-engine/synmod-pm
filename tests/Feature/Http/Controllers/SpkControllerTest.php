<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectSpk;
use VmEngine\SynAuth\Models\Role;

use function Pest\Laravel\get;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('public');
    $this->withoutMiddleware();
    $UserModel = config('auth.providers.users.model');
    $this->user = $UserModel::factory()->create();
    $this->user->roles()->attach(Role::firstOrCreate(['slug' => 'dev'], ['name' => 'Developer', 'level' => 0]));
    $this->actingAs($this->user);
    dbconf(['pm.documents.spk.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('pdf preview streams without storing a document', function () {
    $spk = ProjectSpk::factory()->create();

    get(route('backend.pm.spk.preview', ['id' => $spk->project_id, 'format' => 'pdf']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('cache-control', 'no-store, private');

    expect($spk->project->documents()->count())->toBe(0);
});

test('docx preview downloads', function () {
    $spk = ProjectSpk::factory()->create();

    get(route('backend.pm.spk.preview', ['id' => $spk->project_id, 'format' => 'docx']))
        ->assertOk()
        ->assertDownload();
});

test('an invalid SPK cannot be previewed', function () {
    $spk = ProjectSpk::factory()->create(['scope' => '']);

    get(route('backend.pm.spk.preview', ['id' => $spk->project_id, 'format' => 'pdf']))->assertStatus(422);
});

test('a project without an SPK is not found', function () {
    get(route('backend.pm.spk.preview', ['id' => Project::factory()->create()->id, 'format' => 'pdf']))->assertNotFound();
});
