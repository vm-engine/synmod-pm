<div x-data="{ pageName: '{{ __('pm::division.title') }}', isHome: false, drawerOpen: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Confirmation Dialog -->
    <x-synapse-confirm-dialog />

    <div class="space-y-5 sm:space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ __('pm::division.title') }}
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
                                    <p>{{ __('pm::labels.no') }}</p>
                                </th>
                                <th>
                                    <p>{{ __('pm::division.division') }}</p>
                                </th>
                                <th class="w-1/12">
                                    <p>{{ __('pm::division.division_code') }}</p>
                                </th>
                                <th class="w-1/12">
                                    <p>{{ __('pm::labels.actions') }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse ($this->divisionList as $list)
                                <tr @class(['!bg-blue-50/50 dark:!bg-blue-900/10' => $editId == $list->id])>
                                    <td>
                                        <div class="flex items-center">
                                            <p>{{ $i }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editId == $list->id)
                                                <input
                                                    class="form-input"
                                                    type="text"
                                                    wire:model.defer="editText"
                                                    placeholder="{{ __('pm::division.division_name') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                <p>{{ $list->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            @if ($editId == $list->id)
                                                <input
                                                    class="form-input"
                                                    type="text"
                                                    wire:model.defer="editCode"
                                                    placeholder="{{ __('pm::division.division_code') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            @else
                                                <p>{{ $list->code }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center gap-1">
                                            @if ($editId == $list->id)
                                                <button
                                                    class="btn xs success has-tooltip group"
                                                    type="button"
                                                    wire:click="update({{ $list->id }})"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <span
                                                        class="fa-solid fa-check"
                                                        wire:loading.class.remove="fa-check"
                                                        wire:loading.class="fa-spinner fa-spin"
                                                        wire:target="update({{ $list->id }})"
                                                    ></span>
                                                    <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                                </button>
                                                <button
                                                    class="btn xs danger has-tooltip group"
                                                    type="button"
                                                    wire:click="cancelEdit"
                                                >
                                                    <span class="fa-solid fa-xmark"></span>
                                                    <span class="tooltip">{{ __('pm::labels.cancel') }}</span>
                                                </button>
                                            @endif
                                            @canAccess('pm.master.division.update')
                                            @if ($editId != $list->id)
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    wire:click="edit({{ $list->id }})"
                                                >
                                                    <span class="fa-solid fa-edit"></span>
                                                    <span class="tooltip">{{ __('pm::labels.edit') }}</span>
                                                </button>
                                            @endif
                                            @endcanAccess
                                            @canAccess('pm.master.division.delete')
                                            <button
                                                class="btn-icon danger has-tooltip group"
                                                type="button"
                                                wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::division.titles.delete') }}',
                                                    message: '{{ __('pm::division.messages.delete') }}',
                                                    confirmText: '{{ __('pm::labels.delete') }}',
                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'delete',
                                                    wireParams: ['{{ $list->delete_token }}'],
                                                    wireComponent: '{{ $this->getId() }}'
                                                })"
                                            >
                                                <span class="fa-solid fa-trash"></span>
                                                <span class="tooltip">{{ __('pm::labels.delete') }}</span>
                                            </button>
                                            @endcanAccess
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i2 = 1;
                                @endphp
                                @foreach ($list->subDivisions as $sub)
                                    <tr @class([
                                        'bg-gray-50/50 dark:bg-white/[0.02]',
                                        '!bg-blue-50/50 dark:!bg-blue-900/10' => $editSubId == $sub->id,
                                    ])>
                                        <td>
                                            <div class="flex items-center">
                                                <p class="text-gray-400 dark:text-gray-500">{{ $i . '.' . $i2 }}</p>
                                            </div>
                                        </td>
                                        <td colspan="2">
                                            <div class="flex items-center pl-6">
                                                <i class="fa-solid fa-arrow-turn-down mr-2 text-gray-300 dark:text-gray-600"></i>
                                                @if ($editSubId == $sub->id)
                                                    <input
                                                        class="form-input"
                                                        type="text"
                                                        wire:model.defer="editSubText"
                                                        placeholder="{{ __('pm::division.division_name') }}"
                                                        wire:loading.attr="disabled"
                                                    />
                                                    <button
                                                        class="btn xs success has-tooltip group"
                                                        type="button"
                                                        wire:click="updateSub({{ $sub->id }})"
                                                        wire:loading.attr="disabled"
                                                    >
                                                        <span
                                                            class="fa-solid fa-check"
                                                            wire:loading.class.remove="fa-check"
                                                            wire:loading.class="fa-spinner fa-spin"
                                                            wire:target="updateSub({{ $sub->id }})"
                                                        ></span>
                                                        <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                                    </button>
                                                    <button
                                                        class="btn xs danger has-tooltip group"
                                                        type="button"
                                                        wire:click="cancelEdit"
                                                    >
                                                        <span class="fa-solid fa-xmark"></span>
                                                        <span class="tooltip">{{ __('pm::labels.cancel') }}</span>
                                                    </button>
                                                @else
                                                    <p>{{ $sub->name }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center gap-1">
                                                @canAccess('pm.master.division.update')
                                                @if ($editSubId != $sub->id)
                                                    <button
                                                        class="btn-icon warning has-tooltip group"
                                                        type="button"
                                                        wire:click="editSub({{ $sub->id }})"
                                                    >
                                                        <span class="fa-solid fa-edit"></span>
                                                        <span class="tooltip">{{ __('pm::labels.edit') }}</span>
                                                    </button>
                                                @endif
                                                @endcanAccess
                                                @canAccess('pm.master.division.delete')
                                                <button
                                                    class="btn-icon danger has-tooltip group"
                                                    type="button"
                                                    wire:click="$dispatch('confirm-dialog', {
                                                    title: '{{ __('pm::division.titles.delete_sub') }}',
                                                    message: '{{ __('pm::division.messages.delete_sub') }}',
                                                    confirmText: '{{ __('pm::labels.delete') }}',
                                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                                    confirmColor: 'danger',
                                                    icon: 'fa-solid fa-trash',
                                                    wireMethod: 'deleteSub',
                                                    wireParams: ['{{ $sub->delete_token }}'],
                                                    wireComponent: '{{ $this->getId() }}'
                                                })"
                                                >
                                                    <span class="fa-solid fa-trash"></span>
                                                    <span class="tooltip">{{ __('pm::labels.delete') }}</span>
                                                </button>
                                                @endcanAccess
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $i2++;
                                    @endphp
                                @endforeach
                                <tr class="bg-gray-50/50 dark:bg-white/[0.02]">
                                    <td>
                                        <span class="text-gray-400 dark:text-gray-500">{{ $i . '.' . $i2 }}</span>
                                    </td>
                                    <td colspan="3">
                                        <div class="flex items-center pl-6">
                                            <i class="fa-solid fa-arrow-turn-down mr-2 text-gray-300 dark:text-gray-600"></i>
                                            <input
                                                class="form-input"
                                                type="text"
                                                wire:model.defer="newSubDivision.{{ $list->id }}"
                                                placeholder="{{ __('pm::division.division_name') }}"
                                                wire:loading.attr="disabled"
                                            />
                                            <button
                                                class="btn success has-tooltip group ml-2"
                                                type="button"
                                                wire:click="storeSub({{ $list->id }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <span
                                                    class="fa-solid fa-check"
                                                    wire:loading.class.remove="fa-check"
                                                    wire:loading.class="fa-spinner fa-spin"
                                                    wire:target="storeSub({{ $list->id }})"
                                                ></span>
                                                <span class="tooltip">{{ __('pm::labels.save') }}</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="py-8 text-center">
                                            <span class="fa-solid fa-building text-4xl text-gray-300 dark:text-gray-600"></span>
                                            <p class="mt-3 text-gray-500 dark:text-gray-400">{{ __('pm::division.no_data') }}</p>
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
                                            wire:model.defer="newDivision"
                                            placeholder="{{ __('pm::division.division_name') }}"
                                            wire:loading.attr="disabled"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <input
                                            class="form-input"
                                            type="text"
                                            wire:model.defer="newDivisionCode"
                                            placeholder="{{ __('pm::division.division_code') }}"
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
</div>
