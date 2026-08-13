<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;

uses(RefreshDatabase::class);

test('byProjectCategoryAny scope filters by single category', function () {
    $categoryA = ProjectCategory::factory()->create();
    $categoryB = ProjectCategory::factory()->create();

    $matching = Project::factory()->create(['project_category_id' => $categoryA->id]);
    $nonMatching = Project::factory()->create(['project_category_id' => $categoryB->id]);

    $results = Project::query()->byProjectCategoryAny([$categoryA->id])->get();

    expect($results->pluck('id'))->toContain($matching->id);
    expect($results->pluck('id'))->not->toContain($nonMatching->id);
});

test('byProjectCategoryAny scope filters by multiple categories with ANY match', function () {
    $categoryA = ProjectCategory::factory()->create();
    $categoryB = ProjectCategory::factory()->create();
    $categoryC = ProjectCategory::factory()->create();

    $matchA = Project::factory()->create(['project_category_id' => $categoryA->id]);
    $matchB = Project::factory()->create(['project_category_id' => $categoryB->id]);
    $noMatch = Project::factory()->create(['project_category_id' => $categoryC->id]);

    $results = Project::query()->byProjectCategoryAny([$categoryA->id, $categoryB->id])->get();

    expect($results->pluck('id'))->toContain($matchA->id, $matchB->id);
    expect($results->pluck('id'))->not->toContain($noMatch->id);
});

test('byProjectCategoryAny scope returns all when empty array', function () {
    Project::factory()->count(3)->create();

    $results = Project::query()->byProjectCategoryAny([])->get();

    expect($results)->toHaveCount(3);
});
