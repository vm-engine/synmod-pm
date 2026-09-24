<div x-data="{
    pageName: '{{ __('pm::report.documents.title') }}',
    isHome: false,
    filtersExpanded: true
}">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <!-- Filters Section -->
    <div class="card mb-6">
        <div
            class="card-header cursor-pointer"
            @click="filtersExpanded = !filtersExpanded"
        >
            <div class="flex w-full items-center justify-between">
                <h3 class="card-title">
                    <span class="ph ph-funnel mr-2"></span>
                    {{ __('pm::report.documents.filters.label') }}
                </h3>
                <button
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    type="button"
                >
                    <span
                        class="ph ph-caret-down transition-transform duration-200"
                        :class="{ 'rotate-180': filtersExpanded }"
                    ></span>
                </button>
            </div>
        </div>
        <div
            x-show="filtersExpanded"
            x-collapse
        >
            <div class="card-body">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Year -->
                    <div class="form-box">
                        <label for="year">{{ __('pm::report.documents.filters.year') }}</label>
                        <div class="input-group">
                            <input
                                class="form-input"
                                id="year"
                                name="year"
                                type="number"
                                placeholder="{{ date('Y') }}"
                                wire:model.live.debounce.300ms="year"
                            >
                        </div>
                    </div>

                    <!-- Document Type -->
                    <div class="form-box">
                        <label for="documentType">{{ __('pm::report.documents.filters.type') }}</label>
                        <x-synapse-adv-select
                            id="documentType"
                            name="documentType"
                            wire-model="documentType"
                            :options="$this->documents"
                            :live="true"
                            placeholder="{{ __('pm::report.documents.filters.type') }}"
                        />
                    </div>

                    <!-- Project Category -->
                    <div class="form-box col-span-2">
                        <label for="projectCategory">{{ __('pm::report.documents.filters.project_category') }}</label>
                        <x-synapse-adv-select
                            id="projectCategory"
                            name="projectCategory"
                            wire-model="projectCategories"
                            :live="true"
                            :multiple="true"
                            :options="$this->projectCategoryOptions"
                        ></x-synapse-adv-select>
                    </div>
                </div>

                <!-- Filter Actions -->
                <div class="mt-4 flex items-center gap-3">
                    <button
                        class="btn danger"
                        type="button"
                        wire:click="clearFilters"
                    >
                        <span class="ph ph-funnel-x mr-1"></span>
                        {{ __('pm::report.documents.filters.reset') }}
                    </button>

                    <button
                        class="btn success"
                        type="button"
                        wire:click="exportToExcel"
                        wire:loading.attr="disabled"
                    >
                        <span
                            class="ph ph-file-xls mr-1"
                            wire:loading.remove
                            wire:target="exportToExcel"
                        ></span>
                        <span
                            class="ph ph-spinner syn-spin mr-1"
                            wire:loading
                            wire:target="exportToExcel"
                        ></span>
                        <span
                            wire:loading.remove
                            wire:target="exportToExcel"
                        >{{ __('pm::report.documents.filters.export') }}</span>
                        <span
                            wire:loading
                            wire:target="exportToExcel"
                        >{{ __('pm::report.documents.filters.exporting') }}</span>
                    </button>

                    <div class="ml-auto text-sm text-gray-600 dark:text-gray-400">
                        {{ __('pm::report.documents.filters.results', ['count' => $this->list->count()]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <x-synapse-panel :title="__('pm::report.documents.title')">
        <div class="syn-panel-body">
            <div class="max-w-full overflow-x-auto">
                <table
                    class="datatable min-w-full"
                    width="100%"
                    cellspacing="0"
                >
                    <thead>
                        <tr>
                            <th class="w-1">
                                <p>No.</p>
                            </th>
                            <th>
                                <p>{{ __('pm::report.documents.table.document') }}</p>
                            </th>
                            <th>
                                <p>{{ __('pm::report.documents.table.client') }}</p>
                            </th>
                            <th>
                                <p>{{ __('pm::report.documents.table.project') }}</p>
                            </th>
                            <th>
                                <p>{{ __('pm::report.documents.table.category') }}</p>
                            </th>
                            <th class="w-24 text-center">
                                <p>{{ __('pm::report.documents.table.status') }}</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->list as $index => $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td>
                                    <div class="text-center font-medium text-gray-500">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td>
                                    <div class="space-y-2.5">
                                        @foreach ($project->documents as $document)
                                            @php
                                                $typeName = strtoupper($document->type->name);
                                                $typeColors = match ($typeName) {
                                                    'PROPOSAL'
                                                        => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                    'SPH'
                                                        => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                                    'SPK'
                                                        => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                    'INVOICE'
                                                        => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                                    default
                                                        => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                };
                                            @endphp
                                            <div class="flex items-start gap-3">
                                                <span
                                                    class="inline-flex min-w-[70px] items-center justify-center rounded-md px-2 py-1 text-xs font-bold uppercase tracking-wide {{ $typeColors }}"
                                                >
                                                    {{ $typeName }}
                                                </span>
                                                <div class="min-w-0 flex-1">
                                                    <div class="font-semibold text-gray-900 dark:text-white">
                                                        {{ $document->number }}</div>
                                                    <div
                                                        class="mt-0.5 flex flex-wrap items-center gap-x-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <span>
                                                            <span
                                                                class="ph ph-calendar-blank mr-1 text-gray-400 dark:text-gray-500"
                                                            ></span>
                                                            {{ $document->created_at->format('j M Y') }}
                                                        </span>
                                                        <span>
                                                            <span
                                                                class="ph ph-user mr-1 text-gray-400 dark:text-gray-500"
                                                            ></span>
                                                            {{ $document->creator?->name ?? __('pm::project_document.unknown') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium">
                                        {{ $project->client->company }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $project->name }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if ($project->projectCategory)
                                            <x-synapse-badge
                                                color="brand">{{ $project->projectCategory->name }}</x-synapse-badge>
                                        @else
                                            <span
                                                class="text-gray-400 italic">{{ __('pm::report.documents.table.uncategorized') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex justify-center">
                                        @if ($project->hasSPKDocument())
                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400"
                                                >
                                                    <span class="ph ph-check-circle text-lg"></span>
                                                </span>
                                                <span class="text-xs font-semibold text-green-700 dark:text-green-400">
                                                    {{ __('pm::report.documents.status.deal') }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400"
                                                >
                                                    <span class="ph ph-clock text-lg"></span>
                                                </span>
                                                <span class="text-xs font-semibold text-amber-700 dark:text-amber-400">
                                                    {{ __('pm::report.documents.status.pending') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="syn-empty-state">
                                        <span class="ph ph-file-x syn-empty-state-icon"></span>
                                        <p class="syn-empty-state-text">{{ __('pm::report.documents.table.no_data') }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-synapse-panel>
</div>
