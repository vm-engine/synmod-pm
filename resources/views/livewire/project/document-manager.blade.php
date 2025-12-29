<div class="space-y-4">
    {{-- Confirmation Dialog --}}
    <x-synapse-confirm-dialog />

    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('pm::project.tab_documents') }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Manage project documentation, contracts, and deliverables
            </p>
        </div>
    </div>

    {{-- Filter/Search Bar --}}
    <div class="flex w-full gap-3">
        <div class="min-w-0 flex-1">
            <input
                class="form-input w-full"
                type="text"
                placeholder="Search documents..."
                wire:model.live.debounce.300ms="documentSearch"
            />
        </div>
        <div class="w-48 flex-shrink-0">
            <select
                class="form-input w-full"
                wire:model.live="filterDocType"
            >
                <option value="">All Types</option>
                @foreach (\VmEngine\Pm\Enums\Documents::cases() as $option)
                    <option value="{{ $option->value }}">{{ $option->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Document Groups --}}
    <div class="space-y-3">
        @foreach (\VmEngine\Pm\Enums\Documents::cases() as $document)
            @if ($document->handler()->isActive($project))
                @if ($document->isVersioned())
                    {{-- Versioned Document Group --}}
                    @livewire(
                        'pm.components.document.versioned-document',
                        [
                            'project' => $project,
                            'documentType' => $document,
                            'title' => $document->label(),
                        ],
                        key($document->code(true) . '-' . $project->id)
                    )
                @else
                    {{-- Multiple Document Group --}}
                    @livewire(
                        'pm.components.document.multiple-document',
                        [
                            'project' => $project,
                            'documentType' => $document,
                            'title' => $document->label(),
                        ],
                        key($document->code(true) . '-' . $project->id)
                    )
                @endif
            @endif
        @endforeach
    </div>
</div>
