@props(['documents', 'type' => 'document', 'readonly' => false])

@if (count($documents) === 0)
    {{-- Empty State --}}
    <div class="px-5 py-8 text-center sm:px-6">
        <span class="fa-solid fa-files fa-3x mb-3 text-gray-300 dark:text-gray-700"></span>
        <p class="text-sm text-gray-500 dark:text-gray-500">
            {{ __('pm::project_document.no_files_uploaded') }}
        </p>
        @if (!$readonly)
        <button
            class="btn primary btn-sm mt-3"
            @click="$dispatch('open-modal-upload-{{ $type->code(true) }}'); $wire.dispatch('reset-upload-form')"
        >
            <span class="fa-solid fa-upload mr-2"></span>
            {{ __('pm::project_document.upload_first_file') }}
        </button>
        @endif
    </div>
@else
    {{-- Grid Layout for Multiple Files --}}
    <div class="grid gap-4 p-5 sm:grid-cols-2 sm:p-6 lg:grid-cols-3">
        @foreach ($documents as $document)
            <div
                class="group relative rounded-lg border border-gray-200 bg-white p-4 transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800/50">
                {{-- File Icon & Type --}}
                <div class="mb-3 flex items-start justify-between">
                    @php
                        $extension = strtolower(
                            pathinfo($document->file_info['original_name'] ?? '', PATHINFO_EXTENSION),
                        );
                        $iconClass = match ($extension) {
                            'pdf' => 'fa-file-pdf text-red-600 dark:text-red-400',
                            'doc', 'docx' => 'fa-file-word text-blue-600 dark:text-blue-400',
                            'xls', 'xlsx' => 'fa-file-excel text-green-600 dark:text-green-400',
                            'ppt', 'pptx' => 'fa-file-powerpoint text-orange-600 dark:text-orange-400',
                            'zip', 'rar', '7z' => 'fa-file-zipper text-purple-600 dark:text-purple-400',
                            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'fa-file-image text-pink-600 dark:text-pink-400',
                            default => 'fa-file text-gray-600 dark:text-gray-400',
                        };
                    @endphp
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800">
                        <span class="fa-regular {{ $iconClass }} text-2xl"></span>
                    </div>

                    {{-- Quick Actions (Show on Hover) --}}
                    <div class="flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                        <button
                            class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300"
                            title="{{ __('pm::labels.download') }}"
                            wire:click="downloadDocument({{ $document->id ?? 0 }})"
                        >
                            <span class="fa-solid fa-download text-xs"></span>
                        </button>
                        @if (!$readonly)
                        <button
                            class="flex h-7 w-7 items-center justify-center rounded-md bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400"
                            type="button"
                            title="{{ __('pm::labels.delete') }}"
                            wire:click="$dispatch('confirm-dialog', {
                                title: '{{ __('pm::project_document.delete_file_title') }}',
                                message: '{{ __('pm::project_document.delete_file_message') }}',
                                confirmText: '{{ __('pm::labels.delete') }}',
                                cancelText: '{{ __('pm::labels.cancel') }}',
                                confirmColor: 'danger',
                                icon: 'fa-solid fa-trash',
                                wireMethod: 'confirmDeleteDocument',
                                wireParams: ['{{ $document->delete_token }}'],
                                wireComponent: '{{ $this->getId() }}'
                            })"
                        >
                            <span class="fa-solid fa-trash text-xs"></span>
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Document Info --}}
                <div class="min-w-0">
                    <h6
                        class="truncate font-medium text-gray-800 dark:text-white/90"
                        title="{{ $document->number ?? __('pm::project_document.untitled') }}"
                    >
                        {{ $document->number ?? __('pm::project_document.untitled_document') }}
                    </h6>
                    <p
                        class="mt-1 truncate text-xs text-gray-500"
                        title="{{ $document->file_info['original_name'] ?? '' }}"
                    >
                        {{ $document->file_info['original_name'] ?? __('pm::project_document.default_filename') }}
                    </p>
                </div>

                {{-- Metadata --}}
                <div class="mt-3 space-y-1 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <span class="fa-solid fa-calendar mr-2 w-4"></span>
                        <span>{{ isset($document->created_at) ? $document->created_at->format('M d, Y') : __('pm::project_document.na') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="fa-solid fa-user mr-2 w-4"></span>
                        <span class="truncate">{{ $document->creator->name ?? __('pm::project_document.unknown') }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="fa-solid fa-hard-drive mr-2 w-4"></span>
                        <span>{{ human_filesize($document->file_info['size'] ?? 0) }}</span>
                    </div>
                </div>

                {{-- Notes (if exists) --}}
                @if (isset($document->notes) && $document->notes)
                    <p
                        class="mt-3 line-clamp-2 text-xs text-gray-600 dark:text-gray-400"
                        title="{{ $document->notes }}"
                    >
                        {{ $document->notes }}
                    </p>
                @endif

                {{-- Primary Action Button --}}
                {{-- <button
                    class="btn primary btn-sm mt-3 w-full"
                    wire:click="viewDocument({{ $document->id ?? 0 }})"
                >
                    <span class="fa-solid fa-eye mr-1"></span>
                    View Details
                </button> --}}
            </div>
        @endforeach

        {{-- Add New File Card --}}
        @if (!$readonly)
        <button
            class="flex min-h-[200px] flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 transition-colors hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-700 dark:bg-gray-800/30 dark:hover:border-blue-500 dark:hover:bg-blue-500/5"
            @click="$dispatch('open-modal-upload-{{ $type->code(true) }}'); $wire.dispatch('reset-upload-form')"
        >
            <span class="fa-solid fa-plus text-3xl text-gray-400 dark:text-gray-600"></span>
            <span class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                {{ __('pm::project_document.add_new_file') }}
            </span>
        </button>
        @endif
    </div>
@endif
