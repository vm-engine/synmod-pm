<div x-data="{ pageName: '{{ __('pm::project_category.list_title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ __('pm::project_category.list_title') }}
                </h3>
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
                            @forelse ($this->categoryList as $category)
                                <tr @class(['!bg-blue-50/50 dark:!bg-blue-900/10' => $editId == $category->id])>
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
                                                @canAccess('pm.master.project_category.update')
                                                <button
                                                    class="btn-icon primary has-tooltip group"
                                                    type="button"
                                                    wire:click="loadIndexes({{ $category->id }})"
                                                >
                                                    <span class="fa-solid fa-list-ol"></span>
                                                    <span
                                                        class="tooltip">{{ __('pm::project_category.category_index') }}</span>
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
                                    <td colspan="5">
                                        <div class="py-8 text-center">
                                            <span class="fa-solid fa-folder-tree text-4xl text-gray-300 dark:text-gray-600"></span>
                                            <p class="mt-3 text-gray-500 dark:text-gray-400">{{ __('pm::project_category.no_data') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr class="!bg-gray-50/50 dark:!bg-white/[0.02]">
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
        </div>
    </div>

    <x-synapse-modal
        name="document-index"
        max-width="3xl"
    >
        <div class="p-6">

            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                Document Index
            </h3>

            <form wire:submit.prevent="updateIndexes">
                <div class="max-h-[700px] overflow-y-auto">
                    <table class="datatable">
                        <thead>
                            <tr>
                                <th>
                                    <p>{{ __('pm::config.doc_no_format.fields.label') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::config.doc_no_format.fields.next_c_index') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::config.doc_no_format.fields.next_nc_index') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documentIndexes as $k => $v)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            {{ $v['label'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <input
                                                class="form-input"
                                                type="number"
                                                wire:key="document-index-c-{{ $k }}"
                                                wire:model.defer="documentIndexes.{{ $k }}.C"
                                                wire:loading.attr="disabled"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @isset($v['NC'])
                                                <input
                                                    class="form-input"
                                                    type="number"
                                                    min="1"
                                                    wire:key="document-index-nc-{{ $k }}"
                                                    wire:model.defer="documentIndexes.{{ $k }}.NC"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                &nbsp;
                                            @endisset
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Actions --}}
                <div class="flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <button
                        class="btn btn-outline"
                        type="button"
                        @click="$dispatch('close-modal-document-index');"
                        wire:loading.attr="disabled"
                        wire:target="updateIndexes"
                    >
                        Cancel
                    </button>
                    <button
                        class="btn primary"
                        type="button"
                        wire:click="updateIndexes"
                        wire:loading.attr="disabled"
                        wire:target="updateIndexes"
                    >
                        <span
                            class="fa-solid fa-check mr-1"
                            wire:loading.remove
                            wire:target="updateIndexes"
                        ></span>
                        <span
                            class="fa-solid fa-spinner fa-spin mr-1"
                            wire:loading
                            wire:target="updateIndexes"
                        ></span>
                        <span
                            wire:loading.remove
                            wire:target="updateIndexes"
                        >Update</span>
                        <span
                            wire:loading
                            wire:target="updateIndexes"
                        >Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-synapse-modal>
</div>
