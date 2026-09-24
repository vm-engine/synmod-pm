<div x-data="{ pageName: '{{ __('pm::client_category.title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <x-synapse-panel :title="__('pm::client_category.title')">
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
                                <th class="w-1">
                                    <p>No.</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::labels.name') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::labels.description') }}</p>
                                </th>
                                <th class="datatable-col-actions w-[200px]">
                                    <p>{{ __('pm::labels.actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse ($this->categoryList as $category)
                                <tr @class([
                                    '!bg-blue-50/50 dark:!bg-blue-900/10' => $editId == $category->id,
                                ])>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editId == $category->id)
                                                <input
                                                    class="form-input"
                                                    type="text"
                                                    wire:model.defer="editName"
                                                    placeholder="{{ __('pm::labels.name') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                <p>{{ $category->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editId == $category->id)
                                                <input
                                                    class="form-input"
                                                    type="text"
                                                    wire:model.defer="editDescription"
                                                    placeholder="{{ __('pm::labels.description') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                <p>{{ $category->description }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-1">
                                            @if ($editId == $category->id)
                                                <button
                                                    class="btn success has-tooltip group"
                                                    type="button"
                                                    wire:click="update({{ $category->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <span
                                                        class="fa-solid fa-check"
                                                        wire:loading.class.remove="fa-check"
                                                        wire:loading.class="fa-spinner fa-spin"
                                                        wire:target="update({{ $category->id }})"
                                                    ></span>
                                                    <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                                </button>
                                                <button
                                                    class="btn danger has-tooltip group"
                                                    type="button"
                                                    wire:click="cancelEdit"
                                                >
                                                    <span class="ph ph-x"></span>
                                                    <span class="tooltip">{{ __('pm::labels.cancel') }}</span>
                                                </button>
                                            @else
                                                @canAccess('pm.master.client_category.update')
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    wire:click="edit({{ $category->id }})"
                                                >
                                                    <span class="ph ph-pencil-simple"></span>
                                                    <span class="tooltip">{{ __('pm::labels.edit') }}</span>
                                                </button>
                                                @endcanAccess
                                                @canAccess('pm.master.client_category.delete')
                                                <button
                                                    class="btn-icon danger has-tooltip group"
                                                    type="button"
                                                    wire:click="$dispatch('confirm-dialog', {
                                                            title: '{{ __('pm::client_category.titles.delete') }}',
                                                            message: '{{ __('pm::client_category.messages.delete') }}',
                                                            confirmText: '{{ __('pm::labels.delete') }}',
                                                            cancelText: '{{ __('pm::labels.cancel') }}',
                                                            confirmColor: 'danger',
                                                            icon: 'fa-solid fa-trash',
                                                            wireMethod: 'delete',
                                                            wireParams: ['{{ $category->delete_token }}'],
                                                            wireComponent: '{{ $this->getId() }}'
                                                        })"
                                                >
                                                    <span class="ph ph-trash"></span>
                                                    <span class="tooltip">{{ __('pm::labels.delete') }}</span>
                                                </button>
                                                @endcanAccess
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="syn-empty-state">
                                            <span class="ph ph-tag syn-empty-state-icon"></span>
                                            <p class="syn-empty-state-text">{{ __('pm::client_category.no_data') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="syn-row-muted">
                                <td>
                                    {{ $i }}
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <input
                                            class="form-input"
                                            type="text"
                                            wire:model.defer="newName"
                                            placeholder="{{ __('pm::labels.name') }}"
                                            wire:loading.attr="disabled"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <input
                                            class="form-input"
                                            type="text"
                                            wire:model.defer="newDescription"
                                            placeholder="{{ __('pm::labels.description') }}"
                                            wire:loading.attr="disabled"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <button
                                            class="btn success has-tooltip group"
                                            type="button"
                                            wire:click="store"
                                            wire:loading.attr="disabled"
                                        >
                                            <span
                                                class="fa-solid fa-check"
                                                wire:loading.class.remove="fa-check"
                                                wire:loading.class="fa-spinner fa-spin"
                                                wire:target="store"
                                            ></span>
                                            <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-synapse-panel>
    </div>
</div>
