<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use VmEngine\Pm\Models\ProjectSph;
use VmEngine\Pm\Services\Sph\SphPublisher;
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
    dbconf(['pm.documents.sph.number_format' => '{INDEX}/{PROJECT_CODE}/{DOC_CODE}/{CLIENT_ID}-{CNC}/{MR}/{YYYY}']);
});

test('preview streams a pdf without storing a document', function () {
    $sph = ProjectSph::factory()->create();

    get(route('backend.pm.sph.preview', ['id' => $sph->project_id, 'format' => 'pdf']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertHeader('cache-control', 'no-store, private');

    expect($sph->project->documents()->count())->toBe(0);
});

test('preview downloads a docx', function () {
    $sph = ProjectSph::factory()->create();

    get(route('backend.pm.sph.preview', ['id' => $sph->project_id, 'format' => 'docx']))->assertOk()->assertDownload();
});

test('preview of an invalid SPH is rejected', function () {
    $sph = ProjectSph::factory()->create(['items' => []]);

    get(route('backend.pm.sph.preview', ['id' => $sph->project_id, 'format' => 'pdf']))->assertUnprocessable();
});

test('the published docx can be downloaded', function () {
    $document = app(SphPublisher::class)->publish(ProjectSph::factory()->create(), $this->user->id);

    get(route('backend.pm.document.download_docx', ['id' => $document->id]))->assertOk()->assertDownload($document->file_info['docx_name']);
});
