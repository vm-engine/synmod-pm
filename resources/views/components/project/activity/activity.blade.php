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
                            <th class="w-20">
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
                                            <span class="fa-solid fa-eye"></span>
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
                                <td
                                    class="py-8 text-center text-gray-500 dark:text-gray-400"
                                    colspan="5"
                                >
                                    <span class="fa-solid fa-clock-rotate-left mb-3 block text-4xl"></span>
                                    {{ __('synauth::activity.no_activities') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination with Per Page Selector -->
            @if ($this->activities->hasPages() || $this->activities->total() > 10)
                <div class="mt-4 flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <!-- Per Page Selector -->
                    <div class="flex items-center gap-2">
                        <select
                            class="shadow-theme-sm rounded-sm p-1"
                            id="perPage"
                            name="perPage"
                            wire:model.live="perPage"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>

                    <!-- Pagination Links -->
                    <div class="pagination-container flex-1 grow">
                        {{ $this->activities->links() }}
                    </div>
                </div>
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
                <div class="shrink-0 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between p-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('pm::labels.activity_detail') }}
                        </h3>
                        <button
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            type="button"
                            @click="$dispatch('close-modal-activity-detail')"
                        >
                            <span class="fa-solid fa-times text-xl"></span>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 space-y-4 overflow-y-auto p-6">
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
                <div class="shrink-0 border-t border-gray-200 p-5 dark:border-gray-700">
                    <div class="flex justify-end">
                        <button
                            class="btn secondary"
                            type="button"
                            @click="$dispatch('close-modal-activity-detail')"
                        >
                            <span class="fa-solid fa-times mr-1"></span>
                            {{ __('pm::labels.close') }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </x-synapse-modal>
</div>
