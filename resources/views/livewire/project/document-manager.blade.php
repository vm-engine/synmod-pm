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
        <button class="btn primary btn-sm" wire:click="uploadDocument('general')">
            <span class="fa-solid fa-upload mr-2"></span>
            {{ __('pm::project.upload_document') }}
        </button>
    </div>

    {{-- Filter/Search Bar --}}
    <div class="flex w-full gap-3">
        <div class="flex-1 min-w-0">
            <input
                type="text"
                class="form-input w-full"
                placeholder="Search documents..."
                wire:model.live.debounce.300ms="documentSearch"
            />
        </div>
        <div class="w-48 flex-shrink-0">
            <select class="form-input w-full" wire:model.live="filterDocType">
                <option value="">All Types</option>
                @foreach(\VmEngine\Pm\Enums\Documents::cases() as $docType)
                    <option value="{{ $docType->value }}">{{ $docType->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Document Groups --}}
    <div class="space-y-3">
        {{-- Contract Documents (Single File with Revisions) --}}
        <x-synapse-document-group-card
            type="contract"
            title="Contract Documents"
            description="Official agreements with revision tracking"
            icon="fa-file-contract"
            :documents="$this->contractDocuments"
        />

        {{-- Technical Documents (Single File with Revisions) --}}
        <x-synapse-document-group-card
            type="technical"
            title="Technical Documents"
            description="SOW, TOR, Use Cases, Blueprints"
            icon="fa-file-code"
            :documents="$this->technicalDocuments"
        />

        {{-- Financial Documents (Single File with Revisions) --}}
        <x-synapse-document-group-card
            type="financial"
            title="Financial Documents"
            description="Invoices and receipts with version control"
            icon="fa-file-invoice-dollar"
            :documents="$this->financialDocuments"
        />

        {{-- Deliverables (Multiple Independent Files) --}}
        <x-synapse-document-group-card
            type="deliverable"
            title="Deliverables & Reports"
            description="User guides, UAT results, meeting notes"
            icon="fa-box-archive"
            :documents="$this->deliverableDocuments"
            :multiple-files="true"
        />

        {{-- Backups (Multiple Independent Files) --}}
        <x-synapse-document-group-card
            type="backup"
            title="Backups & Archives"
            description="Source code backups and project archives"
            icon="fa-server"
            :documents="$this->backupDocuments"
            :multiple-files="true"
        />
    </div>
</div>
