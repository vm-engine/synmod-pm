@props(['documents', 'type' => null, 'readonly' => false])

@if (count($documents) === 0)
    {{-- Empty State --}}
    <div class="px-5 py-8 text-center sm:px-6">
        <span class="fa-solid fa-file-pdf fa-3x mb-3 text-gray-300 dark:text-gray-700"></span>
        <p class="text-sm text-gray-500 dark:text-gray-500">
            {{ __('pm::project_document.no_documents_uploaded') }}
        </p>
        @if (!$readonly)
        <button
            class="btn primary btn-sm mt-3"
            @click="$dispatch('open-modal-upload-{{ $type->code(true) }}'); $wire.dispatch('reset-upload-form')"
        >
            <span class="fa-solid fa-upload mr-2"></span>
            {{ __('pm::project_document.upload_first_document') }}
        </button>
        @endif
    </div>
@else
    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @php
            $first = $documents->first();
        @endphp
        <div
            class="px-5 py-4 sm:px-6"
            x-data="{ showVersions: false }"
        >
            {{-- Document Header --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-1 items-start gap-4">
                    {{-- File Icon --}}
                    @php
                        $extension = strtolower(pathinfo($first->file_info['original_name'] ?? '', PATHINFO_EXTENSION));
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
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-gray-800">
                        <span class="fa-regular {{ $iconClass }} text-xl"></span>
                    </div>

                    {{-- Document Info --}}
                    <div class="min-w-0 flex-1">
                        <h5 class="font-medium text-gray-800 dark:text-white/90">
                            {{ $first->number }}
                        </h5>
                        <div
                            class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <span>
                                <span class="fa-solid fa-file-arrow-up mr-1"></span>
                                {{ __('pm::project_document.version') }} {{ $first->version ?? 1 }}
                            </span>
                            <span>
                                <span class="fa-solid fa-calendar mr-1"></span>
                                {{ isset($first->updated_at) ? $first->updated_at->format('M d, Y') : __('pm::project_document.na') }}
                            </span>
                            <span>
                                <span class="fa-solid fa-user mr-1"></span>
                                {{ $first->creator->name ?? __('pm::project_document.unknown') }}
                            </span>
                            <span>
                                <span class="fa-solid fa-hard-drive mr-1"></span>
                                {{ human_filesize($first->file_info['size']) }}
                            </span>
                        </div>

                        {{-- Notes (if exists) --}}
                        @if (isset($first->notes) && $first->notes)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $first->notes }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex shrink-0 flex-wrap gap-2">
                    <button
                        class="btn btn-sm btn-outline"
                        wire:click="downloadDocument({{ $first->id ?? 0 }})"
                    >
                        <span class="fa-solid fa-download"></span>
                    </button>
                    <button
                        class="btn btn-sm btn-outline"
                        @click="showVersions = !showVersions"
                    >
                        <span class="fa-solid fa-clock-rotate-left mr-1"></span>
                        <span
                            x-text="showVersions ? '{{ __('pm::project_document.hide') }}' : '{{ __('pm::project_document.history') }}'"
                        ></span>
                        @if (count($documents) > 1)
                            <span class="ml-1 rounded-full bg-blue-100 px-2 py-0.5 text-xs dark:bg-blue-500/20">
                                {{ count($documents) - 1 }}
                            </span>
                        @endif
                    </button>
                    @if (!$readonly)
                    <button
                        class="btn primary btn-sm"
                        wire:click="uploadNewVersion({{ $first->id ?? 0 }})"
                    >
                        <span class="fa-solid fa-upload mr-1"></span>
                        <span class="hidden sm:inline">{{ __('pm::project_document.new_version') }}</span>
                    </button>
                    <button
                        class="btn danger btn-sm"
                        type="button"
                        wire:click="$dispatch('confirm-dialog', {
                                    title: '{{ __('pm::project_document.delete_document_title') }}',
                                    message: '{{ __('pm::project_document.delete_document_message') }}',
                                    confirmText: '{{ __('pm::labels.delete') }}',
                                    cancelText: '{{ __('pm::labels.cancel') }}',
                                    confirmColor: 'danger',
                                    icon: 'fa-solid fa-trash',
                                    wireMethod: 'confirmDeleteDocument',
                                    wireParams: ['{{ $first->delete_token }}'],
                                    wireComponent: '{{ $this->getId() }}'
                                })"
                    >
                        <span class="fa-solid fa-trash"></span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Version History (Collapsible) --}}
            <div
                class="mt-4"
                x-show="showVersions"
                x-collapse
            >
                <div class="rounded-lg bg-gray-50 px-4 py-3 dark:bg-gray-800/50">
                    <h6 class="mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('pm::project_document.revision_history') }}
                    </h6>
                    @if (count($documents) > 1)
                        <div class="space-y-2">
                            @foreach ($documents->skip(1) as $oldVersion)
                                <div
                                    class="flex items-center justify-between rounded-md bg-white px-3 py-2 text-sm dark:bg-gray-800">
                                    <div class="flex min-w-0 flex-1 items-center gap-3">
                                        <span
                                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400"
                                        >
                                            v{{ $oldVersion->version ?? 1 }}
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="truncate font-medium text-gray-800 dark:text-white/90">
                                                {{ $oldVersion->number }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ isset($oldVersion->created_at) ? $oldVersion->created_at->format('M d, Y g:i A') : __('pm::project_document.na') }}
                                                by
                                                {{ $oldVersion->creator->name ?? __('pm::project_document.unknown') }}
                                                • {{ human_filesize($oldVersion->file_info['size']) }}
                                            </div>
                                            @if (isset($oldVersion->notes) && $oldVersion->notes)
                                                <div class="mt-1 text-xs italic text-gray-600 dark:text-gray-400">
                                                    "{{ $oldVersion->notes }}"
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 gap-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400"
                                            title="{{ __('pm::project_document.download_this_version') }}"
                                            wire:click="downloadDocument({{ $oldVersion->id ?? 0 }})"
                                        >
                                            <span class="fa-solid fa-download"></span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ __('pm::project_document.no_version_history') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
