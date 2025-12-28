<div>
    <x-pm::document-group-card
        :title="$title"
        :badge="$badge"
        :type="$documentType"
    >
        <x-pm::document-revision-list :documents="$this->documents" :type="$documentType" />
    </x-pm::document-group-card>

    {{-- Upload Modal --}}
    <x-synapse-modal name="upload-{{ $documentType }}">
        <div class="p-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                Upload Document - {{ $title }}
            </h3>

            <form wire:submit.prevent="saveDocument" class="space-y-4">
                {{-- File Input --}}
                <div>
                    <label class="form-label required">File</label>
                    <input
                        type="file"
                        wire:model="file"
                        class="form-input w-full"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                    />
                    @error('file')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                    @if ($file)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Selected: {{ $file->getClientOriginalName() }}
                        </p>
                    @endif
                </div>

                {{-- Document Code --}}
                <div>
                    <label class="form-label required">Document Code</label>
                    <input
                        type="text"
                        wire:model="documentCode"
                        class="form-input w-full"
                        placeholder="e.g., DOC-001, CONTRACT-2025-001"
                    />
                    @error('documentCode')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="form-label">Notes</label>
                    <textarea
                        wire:model="notes"
                        class="form-input w-full"
                        rows="3"
                        placeholder="Add any notes or description about this document..."
                    ></textarea>
                    @error('notes')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Footer Actions --}}
                <div class="flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <button type="button" class="btn btn-outline" @click="$dispatch('close-modal-upload-{{ $documentType }}')">
                        Cancel
                    </button>
                    <button type="button" class="btn primary" wire:click="saveDocument">
                        <span class="fa-solid fa-upload mr-1"></span>
                        Upload Document
                    </button>
                </div>
            </form>
        </div>
    </x-synapse-modal>
</div>
