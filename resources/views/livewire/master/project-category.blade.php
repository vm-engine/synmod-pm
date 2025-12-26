<div x-data="{ pageName: '{{ __('pm::project_category.list_title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">
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
                                <th class="w-1">
                                    <p>No.</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::labels.name') }}</p>
                                </th>
                                <th class="w-32">
                                    <p>{{ __('pm::labels.code') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::labels.description') }}</p>
                                </th>
                                <th class="w-[200px]">
                                    <p>{{ __('pm::labels.actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($this->categoryList as $category)
                                <tr>
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
                                                    wire:model.defer="editCode"
                                                    placeholder="{{ __('pm::labels.code') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                <p class="font-mono text-sm">{{ $category->code }}</p>
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
                                        <div class="text-center">
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
                                                    class="btn danger has-tooltip group ml-2"
                                                    type="button"
                                                    wire:click="cancelEdit"
                                                >
                                                    <span class="fa-solid fa-xmark"></span>
                                                    <span class="tooltip">{{ __('pm::labels.cancel') }}</span>
                                                </button>
                                            @else
                                                @canAccess('pm.master.project_category.update')
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    wire:click="edit({{ $category->id }})"
                                                >
                                                    <span class="fa-solid fa-edit"></span>
                                                    <span class="tooltip">{{ __('pm::labels.edit') }}</span>
                                                </button>
                                                @endcanAccess
                                                @canAccess('pm.master.project_category.delete')
                                                <button
                                                    class="btn-icon danger has-tooltip group"
                                                    type="button"
                                                    wire:click="$dispatch('confirm-dialog', {
                                                            title: '{{ __('pm::project_category.delete_title') }}',
                                                            message: '{{ __('pm::project_category.confirm_delete') }}',
                                                            confirmText: '{{ __('pm::labels.delete') }}',
                                                            cancelText: '{{ __('pm::labels.cancel') }}',
                                                            confirmColor: 'danger',
                                                            icon: 'fa-solid fa-trash',
                                                            wireMethod: 'delete',
                                                            wireParams: ['{{ $category->delete_token }}'],
                                                            wireComponent: '{{ $this->getId() }}'
                                                        })"
                                                >
                                                    <span class="fa-solid fa-trash"></span>
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
                            @endforeach
                            <tr>
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
                                            wire:model.defer="newCode"
                                            placeholder="{{ __('pm::labels.code') }}"
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
                                    <div class="text-center">
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
        </div>
    </div>
</div>
