<div x-on:activity-refresh.window="$wire.$refresh()">
    <!-- Activity Log Table -->
    <div class="card">
        <div class="card-body">
            <div class="max-w-full overflow-x-auto">
                <table
                    class="datatable min-w-full"
                    width="100%"
                    cellspacing="0"
                >
                    <thead>
                        <tr>
                            <th
                                class="cursor-pointer"
                                wire:click="sortBy('user_id')"
                            >
                                <p class="flex items-center gap-1">
                                    {{ __('synauth::activity.table.user') }}
                                </p>
                            </th>
                            <th>
                                <p>{{ __('synauth::activity.table.description') }}</p>
                            </th>
                            <th class="datatable-col-actions w-20">
                                <p>{{ __('pm::labels.actions') }}</p>
                            </th>
                            <th>
                                <p>{{ __('synauth::activity.table.ip_address') }}</p>
                            </th>
                            <th
                                class="cursor-pointer"
                                wire:click="sortBy('created_at')"
                            >
                                <p class="flex items-center gap-1">
                                    {{ __('synauth::activity.table.date') }}
                                </p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->activities as $activity)
                            <tr wire:key="activity-{{ $activity->id }}">
                                <td>
                                    @if ($activity->user)
                                        <div>
                                            <p class="font-medium">{{ $activity->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $activity->user->email }}</p>
                                        </div>
                                    @else
                                        <span class="italic text-gray-500 dark:text-gray-400">
                                            {{ __('synauth::activity.system') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-sm">{{ Str::limit($activity->description ?? '-', 80) }}</p>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <button
                                            class="btn-icon info has-tooltip group"
                                            type="button"
                                            @click="$dispatch('open-modal-activity-detail')"
                                            wire:click="showDetail({{ $activity->id }})"
                                        >
                                            <span class="ph ph-eye"></span>
                                            <span class="tooltip">{{ __('pm::labels.view_details') }}</span>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <p class="font-mono text-xs text-gray-600 dark:text-gray-400">
                                        {{ $activity->ip_address ?? '-' }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-sm">{{ $activity->created_at->format('Y-m-d') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $activity->created_at->format('H:i:s') }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="syn-empty-state">
                                        <span class="ph ph-clock-counter-clockwise syn-empty-state-icon"></span>
                                        <p class="syn-empty-state-text">{{ __('synauth::activity.no_activities') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Per Page Selector -->
            @if ($this->activities->hasPages() || $this->activities->total() > 10)
                <x-synapse-per-page-selector :paginator="$this->activities" />
            @endif
        </div>
    </div>

    <!-- Activity Detail Modal -->
    <x-synapse-modal
        name="activity-detail"
        maxWidth="3xl"
    >
        @if ($selectedActivity)
            <div class="flex h-full flex-col">
                <!-- Modal Header -->
                <div class="syn-modal-header shrink-0">
                    <h3 class="syn-modal-title">{{ __('pm::labels.activity_detail') }}</h3>
                    <button
                        class="syn-modal-close"
                        type="button"
                        @click="$dispatch('close-modal-activity-detail')"
                    >
                        <span class="ph ph-x text-xl"></span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="syn-modal-body flex-1 space-y-4 overflow-y-auto">
                    <!-- User Information -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h4 class="mb-3 font-medium text-gray-900 dark:text-white">
                            {{ __('synauth::activity.table.user') }}
                        </h4>
                        @if ($selectedActivity->user)
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedActivity->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $selectedActivity->user->email }}</p>
                            </div>
                        @else
                            <span class="italic text-gray-500 dark:text-gray-400">
                                {{ __('synauth::activity.system') }}
                            </span>
                        @endif
                    </div>

                    <!-- Activity Information -->
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <div class="space-y-3">
                            <!-- Activity Type -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.activity') }}
                                </label>
                                <p class="font-mono text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->activity }}</p>
                            </div>

                            <!-- Feature -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.feature') }}
                                </label>
                                <p class="font-mono text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->feature }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.description') }}
                                </label>
                                <p class="whitespace-pre-wrap text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->description ?? '-' }}</p>
                            </div>

                            <!-- IP Address -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.ip_address') }}
                                </label>
                                <p class="font-mono text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->ip_address ?? '-' }}</p>
                            </div>

                            <!-- User Agent -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.user_agent') }}
                                </label>
                                <p class="break-all text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->user_agent ?? '-' }}</p>
                            </div>

                            <!-- Date & Time -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('synauth::activity.table.date') }}
                                </label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $selectedActivity->created_at->format('Y-m-d H:i:s') }}
                                    <span class="text-gray-500 dark:text-gray-400">
                                        ({{ $selectedActivity->created_at->diffForHumans() }})
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="syn-modal-footer shrink-0">
                    <button
                        class="btn secondary"
                        type="button"
                        @click="$dispatch('close-modal-activity-detail')"
                    >
                        <span class="ph ph-x mr-1"></span>
                        {{ __('pm::labels.close') }}
                    </button>
                </div>
            </div>
        @endif
    </x-synapse-modal>
</div>
