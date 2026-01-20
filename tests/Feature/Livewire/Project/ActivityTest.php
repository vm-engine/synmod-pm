<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\Pm\Livewire\Project\Activity;
use VmEngine\Pm\Models\Project;
use VmEngine\SynAuth\Models\UserActivity;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($this->user = $UserModel::factory()->create());
});

test('component can be rendered', function () {
    $project = Project::factory()->create();

    Livewire::test(Activity::class, ['projectId' => $project->id])
        ->assertStatus(200);
});

test('loads activities for specific project', function () {
    $project = Project::factory()->create();

    // Create activities for this project
    UserActivity::factory()->count(3)->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    // Create activity for different project
    UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.999.test',
    ]);

    // Create activity for different module
    UserActivity::factory()->create([
        'module' => 'other',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id]);

    // Should only show 3 activities for this project
    expect($component->activities)->toHaveCount(3);
});

test('activities are ordered by created_at descending', function () {
    $project = Project::factory()->create();

    $oldActivity = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
        'created_at' => now()->subDays(2),
    ]);

    $newActivity = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
        'created_at' => now(),
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id]);

    $activities = $component->activities;
    expect($activities->first()->id)->toBe($newActivity->id);
    expect($activities->last()->id)->toBe($oldActivity->id);
});

test('activities are paginated', function () {
    $project = Project::factory()->create();

    // Create more activities than perPage
    UserActivity::factory()->count(30)->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id]);

    // Default perPage is 25
    expect($component->activities)->toHaveCount(25);
});

test('can change per page value', function () {
    $project = Project::factory()->create();

    UserActivity::factory()->count(30)->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    Livewire::test(Activity::class, ['projectId' => $project->id])
        ->set('perPage', 10)
        ->assertSet('perPage', 10);
});

// Note: Page reset is handled automatically by updated() method calling resetPage()

test('eager loads user relationship', function () {
    $project = Project::factory()->create();

    UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
        'user_id' => $this->user->id,
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id]);

    $activity = $component->activities->first();
    expect($activity->relationLoaded('user'))->toBeTrue();
    expect($activity->user->name)->toBe($this->user->name);
});

test('can show activity detail', function () {
    $project = Project::factory()->create();

    $activity = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
        'user_id' => $this->user->id,
        'action' => 'test_action',
        'description' => 'Test description',
    ]);

    Livewire::test(Activity::class, ['projectId' => $project->id])
        ->call('showDetail', $activity->id)
        ->assertSet('selectedActivity.id', $activity->id)
        ->assertSet('selectedActivity.action', 'test_action')
        ->assertSet('selectedActivity.description', 'Test description');
});

test('selected activity loads user relationship', function () {
    $project = Project::factory()->create();

    $activity = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
        'user_id' => $this->user->id,
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id])
        ->call('showDetail', $activity->id);

    expect($component->selectedActivity->relationLoaded('user'))->toBeTrue();
    expect($component->selectedActivity->user->id)->toBe($this->user->id);
});

test('resets detail on close modal event', function () {
    $project = Project::factory()->create();

    $activity = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.test',
    ]);

    Livewire::test(Activity::class, ['projectId' => $project->id])
        ->call('showDetail', $activity->id)
        ->assertSet('selectedActivity.id', $activity->id)
        ->dispatch('close-modal-activity-detail')
        ->assertSet('selectedActivity', null);
});

test('handles non-existent activity gracefully', function () {
    $project = Project::factory()->create();

    Livewire::test(Activity::class, ['projectId' => $project->id])
        ->call('showDetail', 99999)
        ->assertSet('selectedActivity', null);
});

test('filters by project id in feature field', function () {
    $project1 = Project::factory()->create();
    $project2 = Project::factory()->create();

    // Create activity for project 1
    $activity1 = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project1->id.'.invoice',
    ]);

    // Create activity for project 2
    $activity2 = UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project2->id.'.invoice',
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project1->id]);

    $activities = $component->activities;
    expect($activities)->toHaveCount(1);
    expect($activities->first()->id)->toBe($activity1->id);
});

test('includes activities with any feature starting with project id', function () {
    $project = Project::factory()->create();

    UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.invoice.create',
    ]);

    UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id.'.client.update',
    ]);

    UserActivity::factory()->create([
        'module' => 'pm',
        'feature' => 'project.'.$project->id,
    ]);

    $component = Livewire::test(Activity::class, ['projectId' => $project->id]);

    expect($component->activities)->toHaveCount(3);
});
