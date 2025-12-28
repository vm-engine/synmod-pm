<div class="space-y-4">
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
                <option value="contract">Contract</option>
                <option value="technical">Technical</option>
                <option value="financial">Financial</option>
                <option value="deliverable">Deliverables</option>
                <option value="backup">Backups</option>
            </select>
        </div>
    </div>

    {{-- Document Groups --}}
    <div class="space-y-3">
        {{-- Contract Documents (Versioned) --}}
        @livewire(
            'pm.components.document.versioned-document',
            [
                'project' => $project,
                'documentType' => 'contract',
                'title' => 'Contract Documents',
                'badge' => 'Versioned',
            ],
            key('contract-' . $project->id)
        )

        {{-- Technical Documents (Versioned) --}}
        @livewire(
            'pm.components.document.versioned-document',
            [
                'project' => $project,
                'documentType' => 'technical',
                'title' => 'Technical Documents',
                'badge' => 'Versioned',
            ],
            key('technical-' . $project->id)
        )

        {{-- Financial Documents (Versioned) --}}
        @livewire(
            'pm.components.document.versioned-document',
            [
                'project' => $project,
                'documentType' => 'financial',
                'title' => 'Financial Documents',
                'badge' => 'Versioned',
            ],
            key('financial-' . $project->id)
        )

        {{-- Deliverables (Multiple Files) --}}
        @livewire(
            'pm.components.document.multiple-document',
            [
                'project' => $project,
                'documentType' => 'deliverable',
                'title' => 'Deliverables & Reports',
                'badge' => 'Multiple Files',
            ],
            key('deliverable-' . $project->id)
        )

        {{-- Backups (Multiple Files) --}}
        @livewire(
            'pm.components.document.multiple-document',
            [
                'project' => $project,
                'documentType' => 'backup',
                'title' => 'Backups & Archives',
                'badge' => 'Multiple Files',
            ],
            key('backup-' . $project->id)
        )
    </div>
</div>
