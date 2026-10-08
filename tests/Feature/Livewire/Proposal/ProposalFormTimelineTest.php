<?php

declare(strict_types=1);

use Livewire\Livewire;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Pm\Models\ProjectProposal;

beforeEach(function () {
    $UserModel = config('auth.providers.users.model');
    $this->actingAs($UserModel::factory()->create());
});

function finishOf(object $component, string $taskId): string
{
    foreach ($component->instance()->scheduledTasks as $task) {
        if ($task->id === $taskId) {
            return $task->finish->toDateString();
        }
    }

    throw new RuntimeException("Task {$taskId} not found");
}

test('indent makes a task a child of the row above and outdent reverses it', function () {
    $proposal = ProjectProposal::factory()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('indentTask', 1);
    expect($component->get('timeline.tasks.1.parent_id'))->toBe('t1');

    $component->call('outdentTask', 1);
    expect($component->get('timeline.tasks.1.parent_id'))->toBeNull();
});

test('move keeps schedule valid', function () {
    $proposal = ProjectProposal::factory()->create();

    // Move "Requirement" (index 1, predecessor t1) above "Kick of Meeting": its predecessor is now below it → cleared
    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('moveRow', 'timeline.tasks', 1, -1);

    expect($component->get('timeline.tasks.0.id'))->toBe('t2')
        ->and($component->get('timeline.tasks.0.predecessor_id'))->toBeNull()
        ->and(count($component->instance()->scheduledTasks))->toBe(9);
});

test('removing a task clears links to it', function () {
    $proposal = ProjectProposal::factory()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('removeRow', 'timeline.tasks', 0);

    expect($component->get('timeline.tasks.0.predecessor_id'))->toBeNull();
});

test('a new task is linked to the previous one', function () {
    $proposal = ProjectProposal::factory()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id])
        ->call('addRow', 'timeline.tasks');

    expect($component->get('timeline.tasks.9.predecessor_id'))->toBe('t9');
});

test('off days and worked holidays change the schedule', function () {
    Holiday::create(['date' => '2026-03-20', 'name' => 'Idul Fitri']);
    $proposal = ProjectProposal::factory()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id]);
    expect(finishOf($component, 't9'))->toBe('2026-05-21')
        ->and($component->instance()->holidaysInSpan)->toBe([['date' => '2026-03-20', 'name' => 'Idul Fitri', 'worked' => false]]);

    $component->call('toggleWorkedHoliday', '2026-03-20');
    expect(finishOf($component, 't9'))->toBe('2026-05-20');

    $component->set('newOffDay', '2026-01-20')->call('addOffDay');
    expect($component->get('timeline.extra_holidays'))->toBe(['2026-01-20'])
        ->and(finishOf($component, 't9'))->toBe('2026-05-21');

    $component->call('removeOffDay', 0);
    expect($component->get('timeline.extra_holidays'))->toBe([]);
});

test('the preview renders an svg per page', function () {
    $proposal = ProjectProposal::factory()->create();

    $component = Livewire::test('pm::proposal.proposal-form', ['id' => $proposal->project_id]);

    expect($component->instance()->ganttPreview)->toHaveCount(1)
        ->and($component->instance()->ganttPreview[0])->toContain('Kick of Meeting');
});
