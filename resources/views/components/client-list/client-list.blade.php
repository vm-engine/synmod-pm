<div x-data="{ pageName: '{{ __('pm::client.list_title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <!-- Client List -->
    <div class="space-y-5 sm:space-y-6">

        <x-synapse-panel :title="__('pm::client.list_title')">
            <x-slot:toolbar>
                @canAccess('pm.client.create')
                <a
                    class="btn primary"
                    href="{{ backend_route('pm.client.create') }}"
                    wire:navigate
                >
                    <span class="ph ph-plus"></span>
                    {{ __('pm::client.add_client') }}
                </a>
                @endcanAccess
                <x-synapse-search-box
                    id="q"
                    name="q"
                    :placeholder="__('pm::client.search_placeholder')"
                    wire:model.live.debounce="q"
                >
                    <x-slot:trailing>
                        <button
                            class="btn"
                            type="button"
                            title="{{ __('pm::client.advanced_filter') }}"
                            @click="drawerOpen = true"
                        ><span class="ph ph-funnel"></span></button>
                    </x-slot:trailing>
                </x-synapse-search-box>
            </x-slot:toolbar>
            <div class="syn-panel-body">
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
                                    <p>{{ __('pm::client.col_pic') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_project_categories') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_status') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::client.col_active') }}</p>
                                </th>
                                <th class="datatable-col-actions">
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
                                            <p>{{ ($this->clientList->currentPage() - 1) * $perPage + $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p class="font-medium">
                                                {{ $client->company }}
                                                @if ($client->npwp_image)
                                                    <button
                                                        class="btn-icon primary ml-2 cursor-pointer"
                                                        type="button"
                                                        title="NPWP Image"
                                                        wire:click="showImage({{ $client->id }})"
                                                    >
                                                        <span class="ph ph-image"></span>
                                                    </button>
                                                @endif
                                            </p>
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
                                                    <span class="ph ph-folder text-xs"></span>
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
                                            <p>{{ $client->pic ?? '-' }}
                                                @if ($client->phone)
                                                    <br><small><b>Phone:</b> {{ $client->phone }}</small>
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @if ($client->projectCategories->count())
                                                @foreach ($client->projectCategories as $category)
                                                    <x-synapse-badge
                                                        color="info"
                                                        size="sm"
                                                        title="{{ __('pm::client.tooltip_project_category') }}"
                                                    >{{ $category->name }}</x-synapse-badge>
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
                                                <x-synapse-badge
                                                    color="warning"
                                                    icon="ph ph-hourglass-medium"
                                                >{{ __('pm::client.prospect') }}</x-synapse-badge>
                                            @else
                                                <x-synapse-badge
                                                    color="success"
                                                    icon="ph ph-check-circle"
                                                >{{ __('pm::client.active') }}</x-synapse-badge>
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
                                                <span class="ph ph-pencil-simple"></span>
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
                                                <span class="ph ph-trash"></span>
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
            <x-synapse-per-page-selector :paginator="$this->clientList" />
        </x-synapse-panel>
    </div>

    @php
        $advanceTitle = '<span class="fa-solid fa-filter mr-2"></span>' . __('pm::client.advanced_filter');
    @endphp
    <x-synapse-drawer :title="$advanceTitle">
        <div class="space-y-6">
            <div>
                <label class="form-label">
                    {{ __('pm::client.filter_category') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterCategories"
                    :live="true"
                    :options="$this->categories"
                    :multiple="true"
                    :preload="$filterCategories"
                    placeholder="{{ __('pm::client.select_categories') }}"
                />
            </div>

            <div>
                <label class="form-label">
                    {{ __('pm::client.filter_project_categories') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterProjectCategories"
                    :live="true"
                    :options="$this->projectCategories"
                    :multiple="true"
                    :preload="$filterProjectCategories"
                    placeholder="{{ __('pm::client.select_project_categories') }}"
                />
            </div>

            <div>
                <label class="form-label">
                    {{ __('pm::client.filter_status') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterStatus"
                    :live="true"
                    :options="[
                        ['value' => '', 'label' => __('pm::client.all')],
                        ['value' => 'prospect', 'label' => __('pm::client.prospect')],
                        ['value' => 'active', 'label' => __('pm::client.active_client')],
                    ]"
                />
            </div>

            <div>
                <label class="form-label">
                    {{ __('pm::client.filter_active_status') }}
                </label>
                <x-synapse-adv-select
                    wire-model="filterActive"
                    :live="true"
                    :options="[
                        ['value' => '', 'label' => __('pm::client.all')],
                        ['value' => 'active', 'label' => __('pm::client.active_filter')],
                        ['value' => 'inactive', 'label' => __('pm::client.inactive_filter')],
                    ]"
                />
            </div>
        </div>
    </x-synapse-drawer>

    <x-synapse-modal name="npwp-image">
        <div class="syn-modal-header">
            <h3 class="syn-modal-title">NPWP Image</h3>
            <button
                class="syn-modal-close"
                type="button"
                @click="$dispatch('close-modal-npwp-image')"
            >
                <span class="ph ph-x text-xl"></span>
            </button>
        </div>
        <div class="syn-modal-body max-h-[90vh] overflow-y-auto">
            <img
                class="h-auto w-full"
                src="{{ $npwpImage }}"
                alt="NPWP Image"
            >
        </div>
    </x-synapse-modal>
</div>
