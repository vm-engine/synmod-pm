<div
    x-data="{ pageName: '{{ __('pm::signature.list_title') }}', isHome: false }"
    x-on:signature-refresh="$wire.$refresh()"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <!-- Header with Search and Add Button -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ __('pm::signature.list_title') }}
                </h3>
                <div class="flex gap-3">
                    @canAccess('pm.master.signature.create')
                    <button
                        class="btn primary"
                        type="button"
                        @click="$dispatch('open-modal-signature-form')"
                                                    wire:click="$dispatch('reset-signature-form')"
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
                            placeholder="{{ __('pm::signature.search_placeholder') }}"
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
                @if ($this->signatureList->count() > 0)
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->signatureList as $signature)
                            <div
                                class="overflow-hidden rounded-lg border border-gray-200 bg-white transition-shadow hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                <!-- Signature Image -->
                                <div class="h-24 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if ($signature->file)
                                        <img
                                            class="h-full w-full object-contain"
                                            src="{{ Storage::url($signature->file) }}"
                                            alt="{{ $signature->name }}"
                                        >
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <span class="fa-solid fa-signature text-4xl text-gray-400"></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="p-4">
                                    <h4 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $signature->name }}
                                    </h4>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-start">
                                            <span class="fa-solid fa-briefcase mt-0.5 w-5 text-gray-400"></span>
                                            <p class="ml-2 font-medium text-gray-700 dark:text-gray-300">
                                                {{ $signature->position }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 flex gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                                        @canAccess('pm.master.signature.update')
                                        <button
                                            class="btn-icon warning btn-sm warning flex-1"
                                            type="button"
                                            @click="$dispatch('open-modal-signature-form')"
                                                    wire:click="$dispatch('load-signature', { id: {{ $signature->id }} })"
                                        >
                                            <span class="fa-solid fa-edit"></span>
                                            {{ __('pm::labels.edit') }}
                                        </button>
                                        @endcanAccess

                                        @canAccess('pm.master.signature.delete')
                                        <button
                                            class="btn-icon danger btn-sm danger flex-1"
                                            type="button"
                                            wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::signature.delete_title') }}',
                                                    message: '{{ __('pm::signature.delete_message', ['name' => addslashes($signature->name)]) }}',
                                                    confirmText: '{{ __('pm::labels.yes_delete') }}',
                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'delete',
                                                    wireParams: ['{{ $signature->delete_token }}'],
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
                        <span class="fa-solid fa-signature text-6xl text-gray-300 dark:text-gray-600"></span>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            {{ __('pm::signature.no_data') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($this->signatureList->hasPages())
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
                        {{ $this->signatureList->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Signature Form Modal -->
    <x-synapse-modal
        name="signature-form"
        maxWidth="2xl"
    >
        <livewire:pm::master.signature-form />
    </x-synapse-modal>
</div>
