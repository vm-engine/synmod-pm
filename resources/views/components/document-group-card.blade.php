@props([
    'type' => 'default',
    'title' => '',
    'badge' => '',
])

<div
    class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
    x-data="{ expanded: true }"
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-5 py-4 sm:px-6">
        <div class="flex flex-1 cursor-pointer items-center gap-3" @click="expanded = !expanded">
            <div>
                <h4 class="font-medium text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h4>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Upload Button --}}
            <button
                class="btn primary btn-sm"
                @click.stop="$dispatch('open-modal-upload-{{ $type }}'); $wire.dispatch('reset-upload-form')"
            >
                <span class="fa-solid fa-upload mr-1"></span>
                <span class="hidden sm:inline">Upload</span>
            </button>

            {{-- Badge and Chevron --}}
            <div class="flex cursor-pointer items-center gap-3" @click="expanded = !expanded">
                @if($badge)
                    <span class="badge badge-primary badge-sm">
                        {{ $badge }}
                    </span>
                @endif
                <span
                    class="fa-solid text-gray-400 transition-transform"
                    :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"
                ></span>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div
        x-show="expanded"
        x-collapse
        class="border-t border-gray-100 dark:border-gray-800"
    >
        {{ $slot }}
    </div>
</div>
