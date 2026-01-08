<div x-data="{ pageName: '{{ __('pm::menu.be.config.index') }}', isHome: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <div class="space-y-5 sm:space-y-6">
        {{-- Tab Navigation --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex border-b border-gray-200 dark:border-gray-800">
                <button
                    class="@if ($activeTab === 'doc_no_format') border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 @else text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 @endif px-6 py-4 text-sm font-medium transition-colors"
                    type="button"
                    wire:click="$set('activeTab', 'doc_no_format')"
                >
                    <span class="fa-solid fa-list-numeric mr-2"></span>
                    {{ __('pm::config.tabs.doc_no_format') }}
                </button>
                <button
                    class="@if ($activeTab === 'general') border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 @else text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 @endif px-6 py-4 text-sm font-medium transition-colors"
                    type="button"
                    wire:click="$set('activeTab', 'general')"
                >
                    <span class="fa-solid fa-gear mr-2"></span>
                    {{ __('pm::config.tabs.general') }}
                </button>
            </div>
        </div>

        @if ($activeTab == 'doc_no_format')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="grid grid grid-cols-2 px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('pm::config.doc_no_format.title') }}
                    </h3>
                    <div class="flex justify-end">
                        <button
                            class="btn primary btn-sm"
                            type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                        >
                            <span
                                class="fa-solid fa-floppy-disk mr-2"
                                wire:loading.remove
                                wire:target="save"
                            ></span>
                            <span
                                class="fa-solid fa-spinner fa-spin mr-2"
                                wire:loading
                                wire:target="save"
                            ></span>
                            {{ __('pm::config.doc_no_format.buttons.save') }}
                        </button>
                    </div>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-800">
                    <form wire:submit="save">
                        <table
                            class="datatable min-w-full"
                            id="dataTable"
                            width="100%"
                            cellspacing="0"
                        >
                            <thead>
                                <tr>
                                    <th class="w-3/12">
                                        <p>{{ __('pm::config.doc_no_format.fields.label') }}</p>
                                    </th>
                                    <th>
                                        <p>
                                            {{ __('pm::config.doc_no_format.fields.number_format') }}
                                        </p>
                                        <small class="text-gray-500">
                                            ({{ __('pm::config.doc_no_format.help.number_format') }})
                                        </small>
                                    </th>
                                    {{-- <th class="w-1/12">
                                        <p>{{ __('pm::config.doc_no_format.fields.next_c_index') }}</p>
                                    </th>
                                    <th class="w-1/12">
                                        <p>{{ __('pm::config.doc_no_format.fields.next_nc_index') }}</p>
                                    </th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @ds($configs)
                                @foreach ($configs as $config)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <span class="font-medium text-gray-800 dark:text-white/90">
                                                    {{ $config['label'] }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                <input
                                                    class="form-input"
                                                    type="text"
                                                    wire:key="docConfigs-format-{{ $config['key'] }}"
                                                    wire:model.defer="docConfigs.format.{{ $config['key'] }}"
                                                    placeholder="{{ __('pm::config.doc_no_format.placeholders.number_format') }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="flex items-center">
                                                <input
                                                    class="form-input"
                                                    type="number"
                                                    min="1"
                                                    wire:key="docConfigs-next_c_index-{{ $config['key'] }}"
                                                    wire:model.defer="docConfigs.next_c_index.{{ $config['key'] }}"
                                                    wire:loading.attr="disabled"
                                                />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                @isset($config['next_nc_index'])
                                                    <input
                                                        class="form-input"
                                                        type="number"
                                                        min="1"
                                                        wire:key="docConfigs-next_nc_index-{{ $config['key'] }}"
                                                        wire:model.defer="docConfigs.next_nc_index.{{ $config['key'] }}"
                                                        wire:loading.attr="disabled"
                                                    />
                                                @endisset
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        @elseif ($activeTab == 'general')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-5 text-center sm:p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        General settings coming soon...
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
