<div x-data="{ pageName: '{{ __('pm::menu.be.config.index') }}', isHome: false }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <div class="space-y-5 sm:space-y-6">
        {{-- Tab Navigation --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex gap-1 border-b border-gray-200 px-2 dark:border-gray-800">
                <button
                    type="button"
                    wire:click="$set('activeTab', 'doc_no_format')"
                    @class([
                        'relative px-5 py-4 text-sm font-medium transition-all',
                        'text-blue-600 dark:text-blue-400' => $activeTab === 'doc_no_format',
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' =>
                            $activeTab !== 'doc_no_format',
                    ])
                >
                    <span class="fa-solid fa-hashtag mr-2"></span>
                    {{ __('pm::config.tabs.doc_no_format') }}
                    @if ($activeTab === 'doc_no_format')
                        <span class="absolute inset-x-0 -bottom-px h-0.5 rounded-full bg-blue-500"></span>
                    @endif
                </button>
                <button
                    type="button"
                    wire:click="$set('activeTab', 'general')"
                    @class([
                        'relative px-5 py-4 text-sm font-medium transition-all',
                        'text-blue-600 dark:text-blue-400' => $activeTab === 'general',
                        'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' =>
                            $activeTab !== 'general',
                    ])
                >
                    <span class="fa-solid fa-sliders mr-2"></span>
                    {{ __('pm::config.tabs.general') }}
                    @if ($activeTab === 'general')
                        <span class="absolute inset-x-0 -bottom-px h-0.5 rounded-full bg-blue-500"></span>
                    @endif
                </button>
            </div>
        </div>

        @if ($activeTab == 'doc_no_format')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                {{-- Card Header --}}
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                    <div>
                        <h3 class="flex items-center gap-2 text-base font-medium text-gray-800 dark:text-white/90">
                            <span
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                            >
                                <span class="fa-solid fa-hashtag text-sm"></span>
                            </span>
                            {{ __('pm::config.doc_no_format.title') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('pm::config.doc_no_format.help.number_format') }}
                        </p>
                    </div>
                    <div class="shrink-0">
                        <button
                            class="btn primary"
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

                {{-- Table Content --}}
                <div class="border-t border-gray-100 dark:border-gray-800">
                    <form wire:submit="save">
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
                                        <th class="w-56">
                                            <p>{{ __('pm::config.doc_no_format.fields.label') }}</p>
                                        </th>
                                        <th>
                                            <p>{{ __('pm::config.doc_no_format.fields.number_format') }}</p>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->configs as $index => $config)
                                        <tr
                                            class="hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                            wire:key="doc_no_format-{{ $index }}"
                                        >
                                            <td>
                                                <div class="text-center font-medium text-gray-500">
                                                    {{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center gap-3">
                                                    @php
                                                        $iconColors = match ($config['key']) {
                                                            'proposal'
                                                                => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                                                            'sph'
                                                                => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                                                            'spk'
                                                                => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                                                            'invoice'
                                                                => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                                            'receipt'
                                                                => 'bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400',
                                                            default
                                                                => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="{{ $iconColors }} inline-flex h-8 w-8 items-center justify-center rounded-lg"
                                                    >
                                                        <span class="fa-solid fa-file-lines text-sm"></span>
                                                    </span>
                                                    <span class="font-semibold text-gray-800 dark:text-white/90">
                                                        {{ $config['label'] }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center">
                                                    <input
                                                        class="form-input font-mono text-sm"
                                                        type="text"
                                                        wire:key="docConfigs-format-{{ $config['key'] }}"
                                                        wire:model="docConfigs.format.{{ $config['key'] }}"
                                                        placeholder="{{ __('pm::config.doc_no_format.placeholders.number_format') }}"
                                                        wire:loading.attr="disabled"
                                                    />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                {{-- Format Help --}}
                <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                    <h4 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span class="fa-solid fa-circle-info mr-2 text-blue-500"></span>
                        {{ __('pm::config.doc_no_format.help.title') }}
                    </h4>
                    <div class="grid gap-2 text-sm text-gray-600 sm:grid-cols-2 lg:grid-cols-3 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{INDEX}</code>
                            <span>{{ __('pm::config.doc_no_format.help.index') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{YYYY}</code>
                            <span>{{ __('pm::config.doc_no_format.help.yyyy') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{YY}</code>
                            <span>{{ __('pm::config.doc_no_format.help.yy') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{MR}</code>
                            <span>{{ __('pm::config.doc_no_format.help.mr') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{PROJECT_CODE}</code>
                            <span>{{ __('pm::config.doc_no_format.help.project_code') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{DIVISION_CODE}</code>
                            <span>{{ __('pm::config.doc_no_format.help.division_code') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{DOC_CODE}</code>
                            <span>{{ __('pm::config.doc_no_format.help.doc_code') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{CNC}</code>
                            <span>{{ __('pm::config.doc_no_format.help.cnc') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <code
                                class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs dark:bg-gray-700">{CLIENT_ID}</code>
                            <span>{{ __('pm::config.doc_no_format.help.client_id') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($activeTab == 'general')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-3 p-5 sm:p-6">
                    <span
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400"
                    >
                        <span class="fa-solid fa-sliders text-sm"></span>
                    </span>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('pm::config.tabs.general') }}
                    </h3>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-800">
                    <div class="py-16 text-center">
                        <span class="fa-solid fa-gear text-5xl text-gray-300 dark:text-gray-600"></span>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            General settings coming soon...
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
