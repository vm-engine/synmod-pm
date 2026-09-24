<div
    x-data="{ pageName: '{{ __('pm::signature.list_title') }}', isHome: false }"
    x-on:signature-refresh="$wire.$refresh()"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <!-- Header with Search and Add Button -->
        <x-synapse-panel :title="__('pm::signature.list_title')">
            <x-slot:toolbar>
                @canAccess('pm.master.signature.create')
                <button
                    class="btn primary"
                    type="button"
                    @click="$dispatch('open-modal-signature-form')"
                    wire:click="$dispatch('reset-signature-form')"
                >
                    <span class="ph ph-plus"></span>
                    {{ __('pm::labels.add') }}
                </button>
                @endcanAccess

                <x-synapse-search-box
                    :placeholder="__('pm::signature.search_placeholder')"
                    wire:model.live.debounce="q"
                />
            </x-slot:toolbar>

            <!-- Card Grid -->
            <div class="syn-panel-body p-5">
                @if ($this->signatureList->count() > 0)
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($this->signatureList as $signature)
                            <div class="syn-media-card">
                                <!-- Signature Image -->
                                <div class="syn-media-card-img h-24">
                                    @if ($signature->file)
                                        <img
                                            class="h-full w-full object-contain"
                                            src="{{ Storage::url($signature->file) }}"
                                            alt="{{ $signature->name }}"
                                        >
                                    @else
                                        <div class="syn-media-card-placeholder">
                                            <span class="ph ph-signature"></span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Body -->
                                <div class="syn-media-card-body">
                                    <h4 class="syn-media-card-title">
                                        {{ $signature->name }}
                                    </h4>

                                    <div class="space-y-2 text-sm">
                                        <div class="flex items-start">
                                            <span class="ph ph-briefcase mt-0.5 w-5 text-gray-400"></span>
                                            <p class="ml-2 font-medium text-gray-700 dark:text-gray-300">
                                                {{ $signature->position }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="syn-media-card-actions">
                                        @canAccess('pm.master.signature.update')
                                        <button
                                            class="btn-icon warning sm flex-1"
                                            type="button"
                                            @click="$dispatch('open-modal-signature-form')"
                                            wire:click="$dispatch('load-signature', { id: {{ $signature->id }} })"
                                        >
                                            <span class="ph ph-pencil-simple"></span>
                                            {{ __('pm::labels.edit') }}
                                        </button>
                                        @endcanAccess

                                        @canAccess('pm.master.signature.delete')
                                        <button
                                            class="btn-icon danger sm flex-1"
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
                        <span class="ph ph-signature syn-empty-state-icon"></span>
                        <p class="syn-empty-state-text">{{ __('pm::signature.no_data') }}</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($this->signatureList->hasPages())
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
                        {{ $this->signatureList->links() }}
                    </div>
                </div>
            @endif
        </x-synapse-panel>
    </div>

    <!-- Signature Form Modal -->
    <x-synapse-modal
        name="signature-form"
        maxWidth="2xl"
    >
        <livewire:pm::master.signature-form />
    </x-synapse-modal>
</div>
