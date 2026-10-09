<div
    x-data
    @open-url.window="window.open($event.detail.url, '_blank')"
>
    <x-pm::document-group-card
        :project="$project"
        :title="$title"
        :badge="$badge"
        :type="$this->documentType"
        :readonly="$readonly"
    >
        @if ($documentTypeValue === \VmEngine\Pm\Enums\Documents::PROPOSAL->value && !$readonly)
            @canAccess('pm.project.update')
            <div class="mb-3 flex justify-end">
                <a
                    class="btn secondary sm"
                    href="{{ backend_route('pm.proposal.edit', ['id' => $project->id]) }}"
                    wire:navigate
                >
                    <span class="ph ph-presentation-chart mr-1"></span>
                    {{ __('pm::proposal.generator_button') }}
                </a>
            </div>
            @endcanAccess
        @endif
        @if ($documentTypeValue === \VmEngine\Pm\Enums\Documents::SPH->value && !$readonly)
            @canAccess('pm.project.update')
            <div class="mb-3 flex justify-end">
                <a
                    class="btn secondary sm"
                    href="{{ backend_route('pm.sph.edit', ['id' => $project->id]) }}"
                    wire:navigate
                >
                    <span class="ph ph-file-text mr-1"></span>
                    {{ __('pm::sph.generator_button') }}
                </a>
            </div>
            @endcanAccess
        @endif
        <x-pm::document-revision-list
            :documents="$this->documents"
            :type="$this->documentType"
            :readonly="$readonly"
        />
    </x-pm::document-group-card>

    {{-- Upload Modal --}}
    @if (!$readonly)
        <x-synapse-modal name="upload-{{ $this->documentType->code(true) }}">
            <div class="syn-modal-header">
                <h3 class="syn-modal-title">Upload Document - {{ $title }}</h3>
                <button
                    class="syn-modal-close"
                    type="button"
                    @click="$dispatch('close-modal-upload-{{ $this->documentType->code(true) }}')"
                >
                    <span class="ph ph-x text-xl"></span>
                </button>
            </div>
            <form wire:submit.prevent="saveDocument">
                <div class="syn-modal-body space-y-4">
                    {{-- Document Code --}}
                    <div>
                        <label class="form-label required">Document Code</label>
                        <input
                            class="form-input w-full"
                            type="text"
                            wire:model="documentCode"
                            placeholder="e.g., DOC-001, CONTRACT-2025-001"
                        />
                        @error('documentCode')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- File Input --}}
                    <div>
                        <label class="form-label required">File</label>
                        <input
                            class="form-input w-full"
                            type="file"
                            wire:model="file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                        />
                        @error('file')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror
                        <div
                            class="mt-1 text-sm text-blue-600 dark:text-blue-400"
                            wire:loading
                            wire:target="file"
                        >
                            <span class="ph ph-spinner syn-spin mr-1"></span>
                            Uploading file...
                        </div>
                        @if ($file)
                            <p class="form-help">
                                Selected: {{ $file->getClientOriginalName() }}
                            </p>
                        @endif
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea
                            class="form-input w-full"
                            wire:model="notes"
                            rows="3"
                            placeholder="Add any notes or description about this document..."
                        ></textarea>
                        @error('notes')
                            <span class="form-error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Version</label>
                        <input
                            class="form-input w-full"
                            type="number"
                            wire:model="version"
                            min="1"
                            placeholder="Enter version number..."
                            readonly
                            disabled
                        />
                    </div>

                </div>
                {{-- Footer Actions --}}
                <div class="syn-modal-footer">
                    <button
                        class="btn secondary"
                        type="button"
                        @click="$dispatch('close-modal-upload-{{ $this->documentType->code(true) }}')"
                        wire:loading.attr="disabled"
                        wire:target="saveDocument"
                    >
                        Cancel
                    </button>
                    <button
                        class="btn primary"
                        type="button"
                        wire:click="saveDocument"
                        wire:loading.attr="disabled"
                        wire:target="saveDocument,file"
                    >
                        <span
                            class="ph ph-upload-simple mr-1"
                            wire:loading.remove
                            wire:target="saveDocument"
                        ></span>
                        <span
                            class="ph ph-spinner syn-spin mr-1"
                            wire:loading
                            wire:target="saveDocument"
                        ></span>
                        <span
                            wire:loading.remove
                            wire:target="saveDocument"
                        >Upload Document</span>
                        <span
                            wire:loading
                            wire:target="saveDocument"
                        >Uploading...</span>
                    </button>
                </div>
            </form>
        </x-synapse-modal>
    @endif
</div>
