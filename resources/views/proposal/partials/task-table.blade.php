@php
    $tasks = $timeline['tasks'] ?? [];
    $scheduled = collect($this->scheduledTasks)->keyBy('id');
@endphp
<div class="overflow-x-auto">
    <table class="table w-full text-sm">
        <thead>
            <tr>
                <th class="w-10">#</th>
                <th>{{ __('pm::proposal.task_name') }}</th>
                <th class="w-24">{{ __('pm::proposal.duration') }}</th>
                <th class="w-48">{{ __('pm::proposal.predecessor') }}</th>
                <th class="w-32">{{ __('pm::proposal.start') }}</th>
                <th class="w-32">{{ __('pm::proposal.finish') }}</th>
                <th class="w-56"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $i => $task)
                @php
                    $row = $scheduled->get($task['id']);
                    $isSummary = $row?->isSummary ?? false;
                @endphp
                <tr wire:key="task-{{ $task['id'] }}">
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <input
                            class="form-input {{ $task['parent_id'] ? 'pl-8!' : '' }} {{ $isSummary ? 'font-semibold' : '' }}"
                            type="text"
                            wire:model.live.blur="timeline.tasks.{{ $i }}.name"
                        />
                    </td>
                    <td>
                        @if ($isSummary)
                            <span class="form-help block px-3">{{ $row->duration }}</span>
                        @else
                            <input
                                class="form-input"
                                type="number"
                                min="1"
                                wire:model.live.blur="timeline.tasks.{{ $i }}.duration"
                            />
                        @endif
                    </td>
                    <td>
                        <select
                            class="form-input"
                            wire:model.live="timeline.tasks.{{ $i }}.predecessor_id"
                        >
                            <option value="">—</option>
                            @foreach (array_slice($tasks, 0, $i) as $p => $candidate)
                                @if ($candidate['id'] !== $task['parent_id'])
                                    <option value="{{ $candidate['id'] }}">{{ $p + 1 }}.
                                        {{ $candidate['name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td class="whitespace-nowrap px-3">{{ $row?->start->format('D d/m/y') }}</td>
                    <td class="whitespace-nowrap px-3">{{ $row?->finish->format('D d/m/y') }}</td>
                    <td>
                        <div class="flex gap-1">
                            <button
                                class="btn-icon"
                                type="button"
                                title="{{ __('pm::proposal.outdent') }}"
                                wire:click="outdentTask({{ $i }})"
                                @disabled(!$task['parent_id'])
                            ><span class="ph ph-text-outdent"></span></button>
                            <button
                                class="btn-icon"
                                type="button"
                                title="{{ __('pm::proposal.indent') }}"
                                wire:click="indentTask({{ $i }})"
                                @disabled($i === 0 || $task['parent_id'])
                            ><span class="ph ph-text-indent"></span></button>
                            @include('pm::proposal.partials.row-actions', [
                                'list' => 'timeline.tasks',
                                'index' => $i,
                                'count' => count($tasks),
                            ])
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<button
    class="btn secondary mt-3"
    type="button"
    wire:click="addRow('timeline.tasks')"
><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_task') }}</button>
