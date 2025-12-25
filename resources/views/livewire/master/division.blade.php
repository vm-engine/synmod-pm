<div x-data="{ pageName: '{{ __('pm::division.title') }}', isHome: false, drawerOpen: false }">
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
                                    <p>Division</p>
                                </th>
                                <th class="w-1">
                                    <p>Actions</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($this->divisionList as $list)
                                <tr>
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
                                                    placeholder="Division Name"
                                                    wire:loading.attr="disabled"
                                                />
                                                <button
                                                    class="btn success has-tooltip group ml-2"
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
                                                    <span class="tooltip">Save</span>
                                                </button>
                                                <button
                                                    class="btn danger has-tooltip group ml-2"
                                                    type="button"
                                                    wire:click="cancelEdit"
                                                >
                                                    <span class="fa-solid fa-xmark"></span>
                                                    <span class="tooltip">Cancel</span>
                                                </button>
                                            @else
                                                <p>{{ $list->name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @canAccess('pm.master.division.edit')
                                            @if ($editId != $list->id)
                                                <button
                                                    class="btn-icon warning has-tooltip group"
                                                    type="button"
                                                    wire:click="edit({{ $list->id }})"
                                                >
                                                    <span class="fa-solid fa-edit"></span>
                                                    <span class="tooltip">Edit</span>
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
                                                <span class="tooltip">Delete</span>
                                            </button>
                                            @endcanAccess
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i2 = 1;
                                @endphp
                                @foreach ($list->subDivisions as $sub)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <p>{{ $i . '.' . $i2 }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-arrow-turn-down mr-2"></i>
                                                @if ($editSubId == $sub->id)
                                                    <input
                                                        class="form-input"
                                                        type="text"
                                                        wire:model.defer="editSubText"
                                                        placeholder="Division Name"
                                                        wire:loading.attr="disabled"
                                                    />
                                                    <button
                                                        class="btn success has-tooltip group ml-2"
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
                                                        <span class="tooltip">Save</span>
                                                    </button>
                                                    <button
                                                        class="btn danger has-tooltip group ml-2"
                                                        type="button"
                                                        wire:click="cancelEdit"
                                                    >
                                                        <span class="fa-solid fa-xmark"></span>
                                                        <span class="tooltip">Cancel</span>
                                                    </button>
                                                @else
                                                    <p>{{ $sub->name }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                @canAccess('pm.master.division.edit')
                                                @if ($editSubId != $sub->id)
                                                    <button
                                                        class="btn-icon warning has-tooltip group"
                                                        type="button"
                                                        wire:click="editSub({{ $sub->id }})"
                                                    >
                                                        <span class="fa-solid fa-edit"></span>
                                                        <span class="tooltip">Edit</span>
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
                                                    <span class="tooltip">Delete</span>
                                                </button>
                                                @endcanAccess
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $i2++;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td>
                                        {{ $i . '.' . $i2 }}
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <i class="fa-solid fa-arrow-turn-down mr-2"></i>
                                            <input
                                                class="form-input"
                                                type="text"
                                                wire:model.defer="newSubDivision.{{ $list->id }}"
                                                placeholder="Division Name"
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
                                                <span class="tooltip">Save</span>
                                            </button>
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
                                            wire:model.defer="newDivision"
                                            placeholder="Division Name"
                                            wire:loading.attr="disabled"
                                        />
                                        <button
                                            class="btn btn-icon success has-tooltip group ml-2"
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
                                            <span class="tooltip">Save</span>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    &nbsp;
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
