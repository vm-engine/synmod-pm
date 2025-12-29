<div x-data="{ pageName: '{{ __('pm::project.list_title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <!-- Project List -->
    <div class="space-y-5 sm:space-y-6">

        <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">

            <div class="flex justify-between">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('pm::project.list_title') }}
                    </h3>
                </div>
                <div class="my-auto flex gap-3 px-5">
                    @canAccess('pm.project.create')
                    <div>
                        <a
                            class="btn primary"
                            href="{{ backend_route('pm.project.create') }}"
                            wire:navigate
                        >
                            <span class="fa-solid fa-plus"></span>
                            {{ __('pm::project.add_project') }}
                        </a>
                    </div>
                    @endcanAccess
                    <div class="input-group">
                        <button
                            class="input-group-item right btn"
                            type="button"
                            title="{{ __('pm::project.filter') }}"
                            @click="drawerOpen = true"
                        ><span class="fa-solid fa-filter"></span></button>
                        <input
                            class="form-input p-2"
                            id="q"
                            name="q"
                            type="text"
                            placeholder="{{ __('pm::project.search') }}"
                            wire:model.live.debounce="q"
                        >
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-800">
                <!-- Card Grid Layout -->
                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($this->projectList as $project)
                        <div
                            class="relative flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:shadow-lg dark:border-gray-700 dark:bg-gray-900/50"
                            wire:key="project-{{ $project->id }}"
                        >
                            <!-- Card Header -->
                            <div class="border-b border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/50">
                                <div class="mb-2 flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $project->name }}
                                        </h4>
                                        <div class="mt-1 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <span class="fa-solid fa-calendar-alt text-xs"></span>
                                            <span>{{ $project->year }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        @php
                                            $statusColor = $project->status ? $project->status->color() : 'gray';
                                            $statusGradient = match($statusColor) {
                                                'green' => 'from-green-500 to-emerald-600',
                                                'blue' => 'from-blue-500 to-cyan-600',
                                                'yellow' => 'from-yellow-500 to-amber-600',
                                                'red' => 'from-red-500 to-rose-600',
                                                'purple' => 'from-purple-500 to-violet-600',
                                                'orange' => 'from-orange-500 to-amber-600',
                                                default => 'from-gray-500 to-gray-600',
                                            };
                                        @endphp
                                        <button
                                            class="{{ in_array($project->status?->value, $filterStatuses)
                                                ? 'bg-gradient-to-br ' . $statusGradient . ' text-white shadow-md'
                                                : 'bg-gradient-to-br ' . $statusGradient . ' text-white opacity-80 hover:opacity-100 hover:shadow-md' }} inline-flex cursor-pointer items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold transition-all"
                                            type="button"
                                            wire:click="filterByStatus({{ $project->status?->value }})"
                                        >
                                            {{ $project->status ? $project->status->label() : '-' }}
                                        </button>
                                        @php
                                            $priorityColor = $project->priority ? $project->priority->color() : 'gray';
                                            $priorityGradient = match($priorityColor) {
                                                'red' => 'from-red-500 to-pink-600',
                                                'orange' => 'from-orange-500 to-red-600',
                                                'yellow' => 'from-yellow-500 to-orange-600',
                                                'green' => 'from-green-500 to-teal-600',
                                                'blue' => 'from-blue-500 to-indigo-600',
                                                default => 'from-gray-500 to-gray-600',
                                            };
                                        @endphp
                                        <button
                                            class="{{ in_array($project->priority?->value, $filterPriorities)
                                                ? 'bg-gradient-to-br ' . $priorityGradient . ' text-white shadow-md'
                                                : 'bg-gradient-to-br ' . $priorityGradient . ' text-white opacity-80 hover:opacity-100 hover:shadow-md' }} inline-flex cursor-pointer items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold transition-all"
                                            type="button"
                                            wire:click="filterByPriority({{ $project->priority?->value }})"
                                        >
                                            {{ $project->priority ? $project->priority->label() : '-' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="flex-1 p-4">
                                <!-- Progress Bar -->
                                <div class="mb-4">
                                    <div class="mb-1 flex items-center justify-between text-sm">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('pm::project.col_progress') }}</span>
                                        <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $project->progress }}%</span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div
                                            class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500"
                                            style="width: {{ $project->progress }}%"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Division & Client -->
                                <div class="mb-4 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="fa-solid fa-building w-4 text-sm text-gray-500 dark:text-gray-400"></span>
                                        @if ($project->division)
                                            <button
                                                class="{{ in_array($project->division_id, $filterDivisions)
                                                    ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300'
                                                    : 'bg-gray-100 text-gray-700 hover:bg-brand-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-brand-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors"
                                                type="button"
                                                wire:click="filterByDivision({{ $project->division_id }})"
                                            >
                                                {{ $project->division->name }}
                                            </button>
                                        @else
                                            <span class="text-sm italic text-gray-400 dark:text-gray-600">-</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="fa-solid fa-user w-4 text-sm text-gray-500 dark:text-gray-400"></span>
                                        @if ($project->client)
                                            <button
                                                class="{{ in_array($project->client_id, $filterClients)
                                                    ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300'
                                                    : 'bg-gray-100 text-gray-700 hover:bg-purple-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-purple-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors"
                                                type="button"
                                                wire:click="filterByClient({{ $project->client_id }})"
                                            >
                                                {{ $project->client->company }}
                                            </button>
                                        @else
                                            <span class="text-sm italic text-gray-400 dark:text-gray-600">-</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Documents -->
                                @php
                                    $documentsByType = $project->documents->groupBy('type');
                                @endphp
                                @if ($documentsByType->isNotEmpty())
                                    <div class="mb-4">
                                        <div class="mb-2 flex items-center justify-between">
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                {{ __('pm::project.documents') }}
                                            </span>
                                            <span class="rounded-full bg-gray-200 px-2 py-0.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $project->documents_count }} {{ __('pm::project.files') }}
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($documentsByType as $type => $docs)
                                                @php
                                                    $docType = \VmEngine\Pm\Enums\Documents::tryFrom($type);
                                                    if (!$docType) continue;
                                                    $docColor = $docType->color();
                                                    $docCount = $docs->count();
                                                    $docIcon = $docType->icon();
                                                    $docCode = $docType->code();
                                                    $docLabel = $docType->label();
                                                    $docGradient = match($docColor) {
                                                        'purple' => 'from-purple-500 to-purple-600',
                                                        'indigo' => 'from-indigo-500 to-indigo-600',
                                                        'blue' => 'from-blue-500 to-blue-600',
                                                        'cyan' => 'from-cyan-500 to-cyan-600',
                                                        'teal' => 'from-teal-500 to-teal-600',
                                                        'green' => 'from-green-500 to-green-600',
                                                        'lime' => 'from-lime-500 to-lime-600',
                                                        'yellow' => 'from-yellow-500 to-yellow-600',
                                                        'amber' => 'from-amber-500 to-amber-600',
                                                        'orange' => 'from-orange-500 to-orange-600',
                                                        'red' => 'from-red-500 to-red-600',
                                                        'pink' => 'from-pink-500 to-pink-600',
                                                        'fuchsia' => 'from-fuchsia-500 to-fuchsia-600',
                                                        'violet' => 'from-violet-500 to-violet-600',
                                                        'rose' => 'from-rose-500 to-rose-600',
                                                        default => 'from-gray-500 to-gray-600',
                                                    };
                                                @endphp
                                                <div class="group/doc relative inline-flex items-center gap-1 rounded-lg bg-gradient-to-br {{ $docGradient }} px-2.5 py-1 text-white shadow-sm transition-all hover:shadow-md">
                                                    <span class="fa-solid {{ $docIcon }} text-xs"></span>
                                                    <span class="text-xs font-semibold">{{ $docCode }}</span>
                                                    @if ($docCount > 1)
                                                        <span class="ml-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-white/30 text-[10px] font-bold">
                                                            {{ $docCount }}
                                                        </span>
                                                    @endif
                                                    <!-- Tooltip -->
                                                    <span class="pointer-events-none absolute -top-10 left-1/2 z-10 -translate-x-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-medium text-white opacity-0 shadow-lg transition-opacity group-hover/doc:opacity-100 dark:bg-gray-700">
                                                        {{ $docLabel }}
                                                        @if ($docCount > 1)
                                                            <span class="ml-1 text-gray-300">({{ __('pm::project.document_count_files', ['count' => $docCount]) }})</span>
                                                        @endif
                                                        <span class="absolute left-1/2 top-full -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></span>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="mt-auto border-t border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-800/30">
                                <div class="flex items-center justify-end gap-1">
                                    @canAccess('pm.project.read')
                                    <a
                                        class="group btn-icon primary has-tooltip"
                                        href="{{ backend_route('pm.project.detail', ['id' => $project->id]) }}"
                                        wire:navigate
                                    >
                                        <span class="fa-solid fa-eye"></span>
                                        <span class="tooltip">{{ __('pm::project.view_detail') }}</span>
                                    </a>
                                    @endcanAccess
                                    @canAccess('pm.project.update')
                                    <a
                                        class="group btn-icon warning has-tooltip"
                                        href="{{ backend_route('pm.project.edit', ['id' => $project->id]) }}"
                                        wire:navigate
                                    >
                                        <span class="fa-solid fa-edit"></span>
                                        <span class="tooltip">{{ __('pm::project.edit_project') }}</span>
                                    </a>
                                    @endcanAccess
                                    @canAccess('pm.project.delete')
                                    <button
                                        class="group btn-icon danger has-tooltip"
                                        type="button"
                                        wire:click="$dispatch('confirm-dialog', {
                                            title: '{{ __('pm::project.delete_project') }}',
                                            message: '{{ __('pm::project.confirm_delete') }}',
                                            confirmText: '{{ __('pm::messages.delete_confirmation_confirm') }}',
                                            cancelText: '{{ __('pm::messages.delete_confirmation_cancel') }}',
                                            confirmColor: 'danger',
                                            icon: 'fa-solid fa-trash',
                                            wireMethod: 'delete',
                                            wireParams: ['{{ $project->delete_token }}'],
                                            wireComponent: '{{ $this->getId() }}'
                                        })"
                                    >
                                        <span class="fa-solid fa-trash"></span>
                                        <span class="tooltip">{{ __('pm::project.delete_project') }}</span>
                                    </button>
                                    @endcanAccess
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                <span class="fa-solid fa-diagram-project text-3xl text-gray-400 dark:text-gray-600"></span>
                            </div>
                            <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">
                                {{ __('pm::project.no_projects_found') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('pm::project.try_different_filters') }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="flex gap-2 p-3">
                <div class="flex-none p-1">
                    <select
                        class="shadow-theme-sm rounded-sm p-1"
                        id="limit"
                        name="limit"
                        wire:model.live="limit"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="pagination-container flex-1 grow">
                    {{ $this->projectList->links() }}
                </div>
            </div>
        </div>
    </div>

    @php
        $advanceTitle = '<span class="fa-solid fa-filter mr-2"></span>' . __('pm::project.filter');
    @endphp
    <x-synapse-drawer :title="$advanceTitle">
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_division') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterDivisions"
                    :live="true"
                    :options="$divisions"
                    :multiple="true"
                    placeholder="{{ __('pm::project.all_divisions') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_client') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterClients"
                    :live="true"
                    :options="$clients"
                    :multiple="true"
                    placeholder="{{ __('pm::project.all_clients') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_services') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterServices"
                    :live="true"
                    :options="$services"
                    :multiple="true"
                    placeholder="{{ __('pm::project.all_divisions') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_status') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterStatuses"
                    :live="true"
                    :options="$statuses"
                    :multiple="true"
                    placeholder="{{ __('pm::project.all_statuses') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_priority') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterPriorities"
                    :live="true"
                    :options="$priorities"
                    :multiple="true"
                    placeholder="{{ __('pm::project.all_priorities') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_year') }}
                </label>
                <x-synapse-select
                    id="filterYear"
                    name="filterYear"
                    wire:model.live="filterYear"
                    :options="array_merge(
                        ['' => __('pm::project.all_years')],
                        collect($years)->pluck('label', 'value')->toArray(),
                    )"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::project.filter_progress') }}
                </label>
                <x-synapse-select
                    id="filterProgress"
                    name="filterProgress"
                    wire:model.live="filterProgress"
                    :options="array_merge(
                        ['' => __('pm::project.all_progress')],
                        collect($progressRanges)->pluck('label', 'value')->toArray(),
                    )"
                />
            </div>
        </div>
    </x-synapse-drawer>
</div>
