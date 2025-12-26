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
                <div class="max-w-full overflow-x-auto">
                    <table
                        class="datatable min-w-full"
                        id="dataTable"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th>
                                    <p>{{ __('pm::project.col_no') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_name') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_division') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_client') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_services') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_status') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_priority') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_progress') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_year') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::project.col_actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($this->projectList as $project)
                                <tr wire:key="project-{{ $project->id }}">
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ ($this->projectList->currentPage() - 1) * $limit + $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-medium">{{ $project->name }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($project->division)
                                                <button
                                                    class="{{ in_array($project->division_id, $filterDivisions)
                                                        ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300'
                                                        : 'bg-gray-100 text-gray-700 hover:bg-brand-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-brand-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium transition-colors"
                                                    type="button"
                                                    wire:click="filterByDivision({{ $project->division_id }})"
                                                >
                                                    <span class="fa-solid fa-building text-xs"></span>
                                                    <span>{{ $project->division->name }}</span>
                                                </button>
                                            @else
                                                <span class="text-sm italic text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($project->client)
                                                <button
                                                    class="{{ in_array($project->client_id, $filterClients)
                                                        ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300'
                                                        : 'bg-gray-100 text-gray-700 hover:bg-purple-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-purple-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium transition-colors"
                                                    type="button"
                                                    wire:click="filterByClient({{ $project->client_id }})"
                                                >
                                                    <span class="fa-solid fa-user text-xs"></span>
                                                    <span>{{ $project->client->company }}</span>
                                                </button>
                                            @else
                                                <span class="text-sm italic text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @if (!empty($project->services))
                                                @foreach ($project->services as $serviceId)
                                                    @php
                                                        $service = \VmEngine\Pm\Enums\Services::tryFrom($serviceId);
                                                    @endphp
                                                    @if ($service)
                                                        <button
                                                            class="{{ in_array($serviceId, $filterServices)
                                                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'
                                                                : 'bg-gray-100 text-gray-700 hover:bg-blue-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-blue-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors"
                                                            type="button"
                                                            wire:click="filterByService({{ $serviceId }})"
                                                        >
                                                            <span>{{ $service->label() }}</span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-sm italic text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @php
                                                $statusColor = $project->status ? $project->status->color() : 'gray';
                                            @endphp
                                            <button
                                                class="{{ in_array($project->status?->value, $filterStatuses)
                                                    ? 'bg-' .
                                                        $statusColor .
                                                        '-200 text-' .
                                                        $statusColor .
                                                        '-800 dark:bg-' .
                                                        $statusColor .
                                                        '-900/50 dark:text-' .
                                                        $statusColor .
                                                        '-200'
                                                    : 'bg-' .
                                                        $statusColor .
                                                        '-100 text-' .
                                                        $statusColor .
                                                        '-800 hover:bg-' .
                                                        $statusColor .
                                                        '-200 dark:bg-' .
                                                        $statusColor .
                                                        '-900/30 dark:text-' .
                                                        $statusColor .
                                                        '-300' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium transition-colors"
                                                type="button"
                                                wire:click="filterByStatus({{ $project->status?->value }})"
                                            >
                                                {{ $project->status ? $project->status->label() : '-' }}
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @php
                                                $priorityColor = $project->priority
                                                    ? $project->priority->color()
                                                    : 'gray';
                                            @endphp
                                            <button
                                                class="{{ in_array($project->priority?->value, $filterPriorities)
                                                    ? 'bg-' .
                                                        $priorityColor .
                                                        '-200 text-' .
                                                        $priorityColor .
                                                        '-800 dark:bg-' .
                                                        $priorityColor .
                                                        '-900/50 dark:text-' .
                                                        $priorityColor .
                                                        '-200'
                                                    : 'bg-' .
                                                        $priorityColor .
                                                        '-100 text-' .
                                                        $priorityColor .
                                                        '-800 hover:bg-' .
                                                        $priorityColor .
                                                        '-200 dark:bg-' .
                                                        $priorityColor .
                                                        '-900/30 dark:text-' .
                                                        $priorityColor .
                                                        '-300' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium transition-colors"
                                                type="button"
                                                wire:click="filterByPriority({{ $project->priority?->value }})"
                                            >
                                                {{ $project->priority ? $project->priority->label() : '-' }}
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1">
                                                <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                                    <div
                                                        class="h-2.5 rounded-full bg-blue-600"
                                                        style="width: {{ $project->progress }}%"
                                                    ></div>
                                                </div>
                                            </div>
                                            <span
                                                class="min-w-[3rem] text-right text-xs text-gray-600 dark:text-gray-400"
                                            >{{ $project->progress }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $project->year }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex justify-center gap-1 text-center">
                                            @canAccess('pm.project.read')
                                            <a
                                                class="btn-icon primary has-tooltip group"
                                                href="{{ backend_route('pm.project.detail', ['id' => $project->id]) }}"
                                                wire:navigate
                                            >
                                                <span class="fa-solid fa-eye"></span>
                                                <span class="tooltip">{{ __('pm::project.view_detail') }}</span>
                                            </a>
                                            @endcanAccess
                                            @canAccess('pm.project.update')
                                            <a
                                                class="btn-icon warning has-tooltip group"
                                                href="{{ backend_route('pm.project.edit', ['id' => $project->id]) }}"
                                                wire:navigate
                                            >
                                                <span class="fa-solid fa-edit"></span>
                                                <span class="tooltip">{{ __('pm::project.edit_project') }}</span>
                                            </a>
                                            @endcanAccess
                                            @canAccess('pm.project.delete')
                                            <button
                                                class="btn-icon danger has-tooltip group"
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
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
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
