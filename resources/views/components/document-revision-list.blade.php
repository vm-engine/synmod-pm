@props(['documents', 'type' => 'document'])

@if(count($documents) === 0)
    {{-- Empty State --}}
    <div class="px-5 py-8 text-center sm:px-6">
        <span class="fa-solid fa-file-pdf fa-3x mb-3 text-gray-300 dark:text-gray-700"></span>
        <p class="text-sm text-gray-500 dark:text-gray-500">
            No documents uploaded yet
        </p>
        <button class="btn primary btn-sm mt-3" @click="$dispatch('open-modal-upload-{{ $type }}'); $wire.dispatch('reset-upload-form')">
            <span class="fa-solid fa-upload mr-2"></span>
            Upload First Document
        </button>
    </div>
@else
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @foreach($documents as $document)
            <div class="px-5 py-4 sm:px-6" x-data="{ showVersions: false }">
                {{-- Document Header --}}
                <div class="flex items-start justify-between gap-4">
                    <div class="flex flex-1 items-start gap-4">
                        {{-- File Icon --}}
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800">
                            <span class="fa-regular fa-file-pdf text-xl text-red-600 dark:text-red-400"></span>
                        </div>

                        {{-- Document Info --}}
                        <div class="min-w-0 flex-1">
                            <h5 class="font-medium text-gray-800 dark:text-white/90">
                                {{ $document->title ?? 'Untitled Document' }}
                            </h5>
                            <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <span>
                                    <span class="fa-solid fa-file-arrow-up mr-1"></span>
                                    Version {{ $document->current_version ?? 1 }}
                                </span>
                                <span>
                                    <span class="fa-solid fa-calendar mr-1"></span>
                                    {{ isset($document->updated_at) ? $document->updated_at->format('M d, Y') : 'N/A' }}
                                </span>
                                <span>
                                    <span class="fa-solid fa-user mr-1"></span>
                                    {{ $document->uploader->name ?? 'Unknown' }}
                                </span>
                                <span>
                                    <span class="fa-solid fa-hard-drive mr-1"></span>
                                    {{ $document->file_size_formatted ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Description (if exists) --}}
                            @if(isset($document->description) && $document->description)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $document->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-shrink-0 flex-wrap gap-2">
                        <button
                            class="btn btn-sm btn-outline"
                            wire:click="downloadDocument({{ $document->id ?? 0 }})"
                        >
                            <span class="fa-solid fa-download"></span>
                        </button>
                        <button
                            class="btn btn-sm btn-outline"
                            @click="showVersions = !showVersions"
                        >
                            <span class="fa-solid fa-clock-rotate-left mr-1"></span>
                            <span x-text="showVersions ? 'Hide' : 'History'"></span>
                            @if(isset($document->versions_count) && $document->versions_count > 1)
                                <span class="ml-1 rounded-full bg-blue-100 px-2 py-0.5 text-xs dark:bg-blue-500/20">
                                    {{ $document->versions_count }}
                                </span>
                            @endif
                        </button>
                        <button
                            class="btn primary btn-sm"
                            wire:click="uploadNewVersion({{ $document->id ?? 0 }})"
                        >
                            <span class="fa-solid fa-upload mr-1"></span>
                            <span class="hidden sm:inline">New Version</span>
                        </button>
                        <button
                            class="btn danger btn-sm"
                            wire:click="confirmDeleteDocument({{ $document->id ?? 0 }})"
                        >
                            <span class="fa-solid fa-trash"></span>
                        </button>
                    </div>
                </div>

                {{-- Version History (Collapsible) --}}
                <div x-show="showVersions" x-collapse class="mt-4">
                    <div class="rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800/50">
                        <h6 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Revision History
                        </h6>
                        @if(isset($document->versions) && count($document->versions) > 0)
                            <div class="space-y-2">
                                @foreach($document->versions as $version)
                                    <div class="flex items-center justify-between rounded-md bg-white px-3 py-2 text-sm dark:bg-gray-800">
                                        <div class="min-w-0 flex-1 flex items-center gap-3">
                                            <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full text-xs font-medium {{ $version->is_current ?? false ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                v{{ $version->version_number ?? 1 }}
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <div class="truncate font-medium text-gray-800 dark:text-white/90">
                                                    {{ $version->original_filename ?? 'document.pdf' }}
                                                    @if($version->is_current ?? false)
                                                        <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">(Current)</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ isset($version->uploaded_at) ? $version->uploaded_at->format('M d, Y g:i A') : 'N/A' }}
                                                    by {{ $version->uploader->name ?? 'Unknown' }}
                                                    • {{ $version->file_size_formatted ?? 'N/A' }}
                                                </div>
                                                @if(isset($version->change_notes) && $version->change_notes)
                                                    <div class="mt-1 text-xs italic text-gray-600 dark:text-gray-400">
                                                        "{{ $version->change_notes }}"
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-shrink-0 gap-2">
                                            <button
                                                class="text-blue-600 hover:text-blue-700 dark:text-blue-400"
                                                wire:click="downloadVersion({{ $version->id ?? 0 }})"
                                                title="Download this version"
                                            >
                                                <span class="fa-solid fa-download"></span>
                                            </button>
                                            @if(!($version->is_current ?? false))
                                                <button
                                                    class="text-green-600 hover:text-green-700 dark:text-green-400"
                                                    wire:click="restoreVersion({{ $version->id ?? 0 }})"
                                                    title="Restore this version"
                                                >
                                                    <span class="fa-solid fa-rotate-left"></span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                No version history available
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
