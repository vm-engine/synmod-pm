<div x-data="{
    pageName: '{{ __('pm::report.documents.title') }}',
    isHome: false,
    filtersExpanded: true
}">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Filters Section -->
    <div class="card mb-6">
        <div
            class="card-header cursor-pointer"
            @click="filtersExpanded = !filtersExpanded"
        >
            <div class="flex w-full items-center justify-between">
                <h3 class="card-title">
                    <span class="fa-solid fa-filter mr-2"></span>
                    {{ __('pm::report.documents.filters.label') }}
                </h3>
                <button
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    type="button"
                >
                    <span
                        class="fa-solid fa-chevron-down transition-transform duration-200"
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
                            :options="$documents"
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
                            :options="$projectCategoryOptions"
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
                        <span class="fa-solid fa-filter-circle-xmark mr-1"></span>
                        {{ __('pm::report.documents.filters.reset') }}
                    </button>

                    <div class="ml-auto text-sm text-gray-600 dark:text-gray-400">
                        {{ __('pm::report.documents.filters.results', ['count' => $this->list->count()]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="max-w-full overflow-x-auto">
                <table
                    class="datatable min-w-full"
                    width="100%"
                    cellspacing="0"
                >
                    <thead>
                        <tr>
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
                            <th>
                                <p>{{ __('pm::report.documents.table.status') }}</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->list as $project)
                            <tr>
                                <td>
                                    <div>
                                        @foreach ($project->documents as $document)
                                            <div>
                                                <b>{{ ucwords($document->type->name) }}</b>:
                                                {{ $document->number }}
                                                (<small>{{ $document->created_at->format('j M Y') }} -
                                                    <span
                                                        class="fa-solid fa-user"></span>{{ $document->creator->name }}</small>)
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div>
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
                                        {!! $project->projectCategory?->name ??
                                            '<i class="text-gray-400">' . __('pm::report.documents.table.uncategorized') . '</i>' !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="items-center text-center">
                                        @if ($project->hasSPKDocument())
                                            <span
                                                class="badge success">{{ __('pm::report.documents.status.deal') }}</span>
                                        @else
                                            <span class="badge">{{ __('pm::report.documents.status.pending') }}</span>
                                        @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="items-center text-center">
                                        <i>{{ __('pm::report.documents.table.no_data') }}</i>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
