<?php

declare(strict_types=1);

use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\Documents\Bast;
use VmEngine\Pm\Services\Documents\Spk;

it('marks the project completed when a BAST is uploaded', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::PLANNING]);

    app(Bast::class)->uploaded($project, Documents::BAST);

    expect($project->fresh()->status)->toBe(ProjectStatus::COMPLETED);
});

it('does not re-save an already completed project when a BAST is uploaded', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::COMPLETED]);
    $updatedAt = $project->updated_at;

    $this->travel(1)->minutes();
    app(Bast::class)->uploaded($project, Documents::BAST);

    expect($project->fresh()->updated_at->equalTo($updatedAt))->toBeTrue();
});

it('moves a prospect project to planning when an SPK is uploaded', function () {
    $project = Project::factory()->create(['status' => ProjectStatus::PROSPECT]);

    app(Spk::class)->uploaded($project, Documents::SPK);

    expect($project->fresh()->status)->toBe(ProjectStatus::PLANNING);
});

it('generates a document number from a stored (string) dbconf index', function () {
    $project = Project::factory()->create();
    $project->client->update(['status' => Client::STATUS_ACTIVE]);
    $categoryCode = $project->projectCategory->code;

    // dbconf stores values in a string column, so they read back as strings.
    dbconf([
        'pm.documents.spk.number_format' => '{INDEX}/{DOC_CODE}/{PROJECT_CODE}',
        "pm.documents.{$categoryCode}.SPK.next_c_index" => 7,
    ]);
    app()->forgetInstance('dbconf'); // drop the in-memory cache so values are read back from the DB, as in a real request

    $number = app(Spk::class)->generateDocumentNumber($project->fresh(), Documents::SPK);

    expect($number)->toBe("007/SPK/{$categoryCode}");
});
