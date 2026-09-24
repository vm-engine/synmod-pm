<div
    x-data="{ pageName: '{{ __('pm::issuer.list_title') }}', isHome: false }"
    x-on:issuer-refresh="$wire.$refresh()"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <!-- Header with Search and Add Button -->
        <x-synapse-panel :title="__('pm::issuer.list_title')">
            <x-slot:toolbar>
                @canAccess('pm.master.issuer.create')
                <button
                    class="btn primary"
                    type="button"
                    @click="$dispatch('open-modal-issuer-form')"
                    wire:click="$dispatch('reset-issuer-form')"
                >
                    <span class="ph ph-plus"></span>
                    {{ __('pm::labels.add') }}
                </button>
                @endcanAccess

                <x-synapse-search-box
                    :placeholder="__('pm::issuer.search_placeholder')"
                    wire:model.live.debounce="q"
                />
            </x-slot:toolbar>

            <!-- Card Grid -->
            <div class="syn-panel-body p-5">
                @if ($this->issuerList->count() > 0)
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        @foreach ($this->issuerList as $issuer)
                            <div class="syn-media-card">
                                <!-- Header Image -->
                                <div class="syn-media-card-img h-32">
                                    @if ($issuer->header_image)
                                        <img
                                            class="h-full w-full object-contain"
                                            src="{{ Storage::url($issuer->header_image) }}"
                                            alt="{{ $issuer->name }}"
                                        >
                                    @else
                                        <div class="syn-media-card-placeholder">
                                            <span class="ph ph-buildings"></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="syn-media-card-body">
                                    <h4 class="syn-media-card-title">
                                        {{ $issuer->name }}
                                    </h4>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-start">
                                            <span class="ph ph-invoice mt-0.5 w-5 text-gray-400"></span>
                                            <div class="ml-2">
                                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $issuer->tax_id_name }}
                                                </p>
                                                <p class="font-mono text-gray-500 dark:text-gray-400">
                                                    {{ $issuer->tax_id_number }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <span class="ph ph-map-pin mt-0.5 w-5 text-gray-400"></span>
                                            <p class="ml-2 text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($issuer->tax_id_address, 80) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="syn-media-card-actions">
                                        @canAccess('pm.master.issuer.update')
                                        <button
                                            class="btn-icon warning sm flex-1"
                                            type="button"
                                            @click="$dispatch('open-modal-issuer-form')"
                                            wire:click="$dispatch('load-issuer', { id: {{ $issuer->id }} })"
                                        >
                                            <span class="ph ph-pencil-simple"></span>
                                            {{ __('pm::labels.edit') }}
                                        </button>
                                        @endcanAccess

                                        @canAccess('pm.master.issuer.delete')
                                        <button
                                            class="btn-icon danger sm flex-1"
                                            type="button"
                                            wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::issuer.delete_title') }}',
                                                    message: '{{ __('pm::issuer.delete_message', ['name' => addslashes($issuer->name)]) }}',
                                                    confirmText: '{{ __('pm::labels.yes_delete') }}',
                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'delete',
                                                    wireParams: ['{{ $issuer->delete_token }}'],
                                                    wireComponent: '{{ $this->getId() }}'
                                                })"
                                        >
                                            <span class="ph ph-trash"></span>
                                            {{ __('pm::labels.delete') }}
                                        </button>
                                        @endcanAccess
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="syn-empty-state">
                        <span class="ph ph-buildings syn-empty-state-icon"></span>
                        <p class="syn-empty-state-text">{{ __('pm::issuer.no_data') }}</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($this->issuerList->hasPages())
                <div class="syn-per-page-selector syn-panel-body">
                    <div class="flex-none p-1">
                        <select
                            class="syn-per-page-select"
                            wire:model.live="limit"
                        >
                            <option value="6">6</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                            <option value="48">48</option>
                        </select>
                    </div>
                    <div class="pagination-container flex-1 grow">
                        {{ $this->issuerList->links() }}
                    </div>
                </div>
            @endif
        </x-synapse-panel>
    </div>

    <!-- Issuer Form Modal -->
    <x-synapse-modal
        name="issuer-form"
        maxWidth="2xl"
    >
        <livewire:pm::master.issuer-form />
    </x-synapse-modal>
</div>
