@include('pm::proposal.partials.tab-errors', ['key' => 'timeline'])
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card">
            <div class="card-body">
                <div class="form-box required">
                    <label for="timeline-start">{{ __('pm::proposal.start_date') }}</label>
                    <x-synapse-datepicker
                        id="timeline-start"
                        name="timeline_start"
                        wire:model.live="timeline.start_date"
                    />
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('pm::proposal.holidays_in_span') }}</h3>
            </div>
            <div class="card-body space-y-2">
                @forelse ($this->holidaysInSpan as $holiday)
                    <label
                        class="flex items-center justify-between gap-2"
                        wire:key="holiday-{{ $holiday['date'] }}"
                    >
                        <span>{{ \Carbon\Carbon::parse($holiday['date'])->format('D d/m/Y') }} —
                            {{ $holiday['name'] }}</span>
                        <span class="flex items-center gap-1 text-sm">
                            <input
                                type="checkbox"
                                @checked($holiday['worked'])
                                wire:click="toggleWorkedHoliday('{{ $holiday['date'] }}')"
                            />
                            {{ __('pm::proposal.work_this_day') }}
                        </span>
                    </label>
                @empty
                    <p class="form-help">{{ __('pm::proposal.no_holidays') }}</p>
                @endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('pm::proposal.extra_off_days') }}</h3>
            </div>
            <div class="card-body space-y-2">
                @foreach ($timeline['extra_holidays'] ?? [] as $i => $date)
                    <div
                        class="flex items-center justify-between"
                        wire:key="off-{{ $date }}"
                    >
                        <span>{{ \Carbon\Carbon::parse($date)->format('D d/m/Y') }}</span>
                        <button
                            class="btn-icon danger"
                            type="button"
                            wire:click="removeOffDay({{ $i }})"
                        ><span class="ph ph-trash"></span></button>
                    </div>
                @endforeach
                <div class="flex gap-2">
                    <x-synapse-datepicker
                        id="new-off-day"
                        name="new_off_day"
                        wire:model="newOffDay"
                    />
                    <button
                        class="btn secondary"
                        type="button"
                        wire:click="addOffDay"
                    >{{ __('pm::proposal.add_off_day') }}</button>
                </div>
                @error('newOffDay')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @include('pm::proposal.partials.task-table')
        </div>
    </div>

    <div class="card">
        <div class="card-body space-y-4 overflow-x-auto">
            @foreach ($this->ganttPreview as $svg)
                <div class="rounded bg-white p-2">{!! $svg !!}</div>
            @endforeach
        </div>
    </div>
</div>
