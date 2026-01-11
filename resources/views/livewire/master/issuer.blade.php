<div
    x-data="{ pageName: '{{ __('pm::issuer.list_title') }}', isHome: false }"
    x-on:issuer-refresh="$wire.$refresh()"
>
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <!-- Header with Search and Add Button -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ __('pm::issuer.list_title') }}
                </h3>
                <div class="flex gap-3">
                    @canAccess('pm.master.issuer.create')
                    <button
                        class="btn primary"
                        type="button"
                        @click="$dispatch('open-modal-issuer-form'); $wire.dispatch('reset-issuer-form')"
                    >
                        <span class="fa-solid fa-plus"></span>
                        {{ __('pm::labels.add') }}
                    </button>
                    @endcanAccess

                    <!-- Search Input -->
                    <div class="input-group">
                        <input
                            class="form-input p-2"
                            type="text"
                            placeholder="{{ __('pm::issuer.search_placeholder') }}"
                            wire:model.live.debounce="q"
                        >
                        <span class="input-group-item right">
                            <span class="fa-solid fa-search text-gray-400"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Grid -->
            <div class="border-t border-gray-100 p-5 dark:border-gray-800">
                @if ($this->issuerList->count() > 0)
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        @foreach ($this->issuerList as $issuer)
                            <div
                                class="overflow-hidden rounded-lg border border-gray-200 bg-white transition-shadow hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <!-- Header Image -->
                                <div class="h-32 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if ($issuer->header_image)
                                        <img
                                            class="h-full w-full object-contain"
                                            src="{{ Storage::url($issuer->header_image) }}"
                                            alt="{{ $issuer->name }}"
                                        >
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <span class="fa-solid fa-building text-4xl text-gray-400"></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="p-4">
                                    <h4 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $issuer->name }}
                                    </h4>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-start">
                                            <span class="fa-solid fa-file-invoice mt-0.5 w-5 text-gray-400"></span>
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
                                            <span class="fa-solid fa-location-dot mt-0.5 w-5 text-gray-400"></span>
                                            <p class="ml-2 text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($issuer->tax_id_address, 80) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 flex gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                                        @canAccess('pm.master.issuer.update')
                                        <button
                                            class="btn-icon warning btn-sm warning flex-1"
                                            type="button"
                                            @click="$dispatch('open-modal-issuer-form'); $wire.dispatch('load-issuer', { id: {{ $issuer->id }} })"
                                        >
                                            <span class="fa-solid fa-edit"></span>
                                            {{ __('pm::labels.edit') }}
                                        </button>
                                        @endcanAccess

                                        @canAccess('pm.master.issuer.delete')
                                        <button
                                            class="btn-icon danger btn-sm danger flex-1"
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
                                            <span class="fa-solid fa-trash"></span>
                                            {{ __('pm::labels.delete') }}
                                        </button>
                                        @endcanAccess
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <span class="fa-solid fa-building text-6xl text-gray-300 dark:text-gray-600"></span>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            {{ __('pm::issuer.no_data') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($this->issuerList->hasPages())
                <div class="flex gap-2 border-t border-gray-100 p-3 dark:border-gray-800">
                    <div class="flex-none p-1">
                        <select
                            class="shadow-theme-sm rounded-sm p-1"
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
        </div>
    </div>

    <!-- Issuer Form Modal -->
    <x-synapse-modal
        name="issuer-form"
        maxWidth="2xl"
    >
        <livewire:pm.master.issuer-form />
    </x-synapse-modal>
</div>
