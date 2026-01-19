<div
    x-data
    @open-url.window="window.open($event.detail.url, '_blank')"
>
    <x-pm::document-group-card
        :project="$project"
        :title="$title"
        :badge="$badge"
        :type="$documentType"
        :readonly="$readonly"
    >
        <x-pm::document-multiple-files-list
            :documents="$this->documents"
            :type="$documentType"
            :readonly="$readonly"
        />
    </x-pm::document-group-card>

    {{-- Upload Modal --}}
    @if (!$readonly)
    <x-synapse-modal name="upload-{{ $documentType->code(true) }}">
        <div class="p-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                Upload File - {{ $title }}
            </h3>

            <form
                class="space-y-4"
                wire:submit.prevent="saveDocument"
            >
                {{-- Document Code --}}
                <div>
                    <label class="form-label required">Document Code</label>
                    <input
                        class="form-input w-full"
                        type="text"
                        wire:model="documentCode"
                        placeholder="e.g., DELIV-001, BACKUP-2025-001"
                    />
                    @error('documentCode')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- File Input --}}
                <div>
                    <label class="form-label required">File</label>
                    <input
                        class="form-input w-full"
                        type="file"
                        wire:model="file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                    />
                    @error('file')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                    <div
                        class="mt-1 text-sm text-blue-600 dark:text-blue-400"
                        wire:loading
                        wire:target="file"
                    >
                        <span class="fa-solid fa-spinner fa-spin mr-1"></span>
                        Uploading file...
                    </div>
                    @if ($file)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
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
                        placeholder="Add any notes or description about this file..."
                    ></textarea>
                    @error('notes')
                        <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Footer Actions --}}
                <div class="flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700">
                    <button
                        class="btn btn-outline"
                        type="button"
                        @click="$dispatch('close-modal-upload-{{ $documentType->code(true) }}')"
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
                            class="fa-solid fa-upload mr-1"
                            wire:loading.remove
                            wire:target="saveDocument"
                        ></span>
                        <span
                            class="fa-solid fa-spinner fa-spin mr-1"
                            wire:loading
                            wire:target="saveDocument"
                        ></span>
                        <span
                            wire:loading.remove
                            wire:target="saveDocument"
                        >Upload File</span>
                        <span
                            wire:loading
                            wire:target="saveDocument"
                        >Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-synapse-modal>
    @endif
</div>
