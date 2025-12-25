<div x-data="{ pageName: '{{ __('pm::client.list_title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <!-- Client List -->
    <div class="space-y-5 sm:space-y-6">

        <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">

            <div class="flex justify-between">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('pm::client.list_title') }}
                    </h3>
                </div>
                <div class="my-auto flex gap-3 px-5">
                    @canAccess('pm.client.create')
                    <div>
                        <a
                            class="btn primary"
                            href="{{ backend_route('pm.client.create') }}"
                            wire:navigate
                        >
                            <span class="fa-solid fa-plus"></span>
                            {{ __('pm::client.add_client') }}
                        </a>
                    </div>
                    @endcanAccess
                    <div class="input-group">
                        <button
                            class="input-group-item right btn"
                            type="button"
                            title="{{ __('pm::client.advanced_filter') }}"
                            @click="drawerOpen = true"
                        ><span class="fa-solid fa-filter"></span></button>
                        <input
                            class="form-input p-2"
                            id="q"
                            name="q"
                            type="text"
                            placeholder="{{ __('pm::client.search_placeholder') }}"
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
                                    <p>{{ __('pm::client.no') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_company') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_category') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_director') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_services') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_status') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_active') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($this->clientList as $client)
                                <tr wire:key="client-{{ $client->id }}">
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ ($this->clientList->currentPage() - 1) * $limit + $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-medium">{{ $client->company }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($client->category)
                                                <button
                                                    class="{{ in_array($client->category_id, $filterCategories)
                                                        ? 'bg-brand-100 text-brand-700 dark:bg-brand-900/30 dark:text-brand-300'
                                                        : 'bg-gray-100 text-gray-700 hover:bg-brand-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-brand-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium transition-colors"
                                                    type="button"
                                                    title="{{ __('pm::client.tooltip_filter_category') }}"
                                                    wire:click="filterByCategory({{ $client->category_id }})"
                                                >
                                                    <span class="fa-solid fa-folder text-xs"></span>
                                                    <span>{{ $client->category->name }}</span>
                                                </button>
                                            @else
                                                <span
                                                    class="text-sm italic text-gray-400 dark:text-gray-600">{{ __('pm::client.no_category') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $client->director ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @if (!empty($client->services))
                                                @foreach ($client->services as $serviceId)
                                                    @php
                                                        $service = \VmEngine\Pm\Enums\Services::tryFrom($serviceId);
                                                    @endphp
                                                    @if ($service)
                                                        <button
                                                            class="{{ in_array($serviceId, $filterServices)
                                                                ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'
                                                                : 'bg-gray-100 text-gray-700 hover:bg-blue-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-blue-900/20' }} inline-flex cursor-pointer items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors"
                                                            type="button"
                                                            title="{{ __('pm::client.tooltip_filter_service') }}"
                                                            wire:click="filterByService({{ $serviceId }})"
                                                        >
                                                            <span>{{ $service->label() }}</span>
                                                        </button>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span
                                                    class="text-sm italic text-gray-400 dark:text-gray-600">{{ __('pm::client.none') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($client->status === \VmEngine\Pm\Models\Client::STATUS_PROSPECT)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2.5 py-0.5 text-sm font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300"
                                                >
                                                    <span class="fa-solid fa-hourglass-half text-xs"></span>
                                                    {{ __('pm::client.prospect') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300"
                                                >
                                                    <span class="fa-solid fa-check-circle text-xs"></span>
                                                    {{ __('pm::client.active') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center">
                                            <x-synapse-toggler
                                                :checked="$client->active"
                                                @change="$wire.toggleActive({{ $client->id }})"
                                                wire:loading.attr="disabled"
                                                :inline="true"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @canAccess('pm.client.update')
                                            <a
                                                class="btn-icon warning has-tooltip group"
                                                href="{{ backend_route('pm.client.edit', ['id' => $client->id]) }}"
                                                wire:navigate
                                            >
                                                <span class="fa-solid fa-edit"></span>
                                                <span class="tooltip">{{ __('pm::client.tooltip_edit') }}</span>
                                            </a>
                                            @endcanAccess
                                            @canAccess('pm.client.delete')
                                            <button
                                                class="btn-icon danger has-tooltip group"
                                                type="button"
                                                wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::messages.delete_confirmation_title') }}',
                                                    message: '{{ __('pm::messages.delete_confirmation_message', ['company' => addslashes($client->company)]) }}',
                                                    confirmText: '{{ __('pm::messages.delete_confirmation_confirm') }}',
                                                    cancelText: '{{ __('pm::messages.delete_confirmation_cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'delete',
                                                    wireParams: ['{{ $client->delete_token }}'],
                                                    wireComponent: '{{ $this->getId() }}'
                                                })"
                                            >
                                                <span class="fa-solid fa-trash"></span>
                                                <span class="tooltip">{{ __('pm::client.tooltip_delete') }}</span>
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
                    {{ $this->clientList->links() }}
                </div>
            </div>
        </div>
    </div>

    @php
        $advanceTitle = '<span class="fa-solid fa-filter mr-2"></span>' . __('pm::client.advanced_filter');
    @endphp
    <x-synapse-drawer :title="$advanceTitle">
        <div class="space-y-6">
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::client.filter_category') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterCategories"
                    :live="true"
                    :options="$categories"
                    :multiple="true"
                    placeholder="{{ __('pm::client.select_categories') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::client.filter_services') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterServices"
                    :live="true"
                    :options="$services"
                    :multiple="true"
                    placeholder="{{ __('pm::client.select_services') }}"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::client.filter_status') }}
                </label>
                <x-synapse-select
                    id="filterStatus"
                    name="filterStatus"
                    wire:model.live="filterStatus"
                    :options="[
                        '' => __('pm::client.all'),
                        'prospect' => __('pm::client.prospect'),
                        'active' => __('pm::client.active_client'),
                    ]"
                />
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('pm::client.filter_active_status') }}
                </label>
                <x-synapse-select
                    id="filterActive"
                    name="filterActive"
                    wire:model.live="filterActive"
                    :options="[
                        '' => __('pm::client.all'),
                        'active' => __('pm::client.active_filter'),
                        'inactive' => __('pm::client.inactive_filter'),
                    ]"
                />
            </div>
        </div>
    </x-synapse-drawer>
</div>
