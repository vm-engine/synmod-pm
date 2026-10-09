<div
    class="relative overflow-y-auto"
    x-data="{ pageName: '{{ __('pm::sph.page_title') }}', isHome: false, tab: 'general' }"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])
    <x-synapse-confirm-dialog />

    @php
        $sph = $this->sph;
        $tabs = [
            'general' => __('pm::sph.tab_general'),
            'items' => __('pm::sph.tab_items'),
            'terms' => __('pm::sph.tab_terms'),
            'timeline' => __('pm::sph.tab_timeline'),
            'attachments' => __('pm::sph.tab_attachments'),
        ];
    @endphp

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <x-synapse-badge :color="$sph->isPublished() ? 'success' : 'gray'">{{ $sph->status->label() }}</x-synapse-badge>
        <span class="font-mono">{{ $sph->number ?? '—' }}</span>
        @if ($sph->isPublished())
            <span class="form-help">
                @if ($sph->has_unpublished_changes)
                    {{ __('pm::sph.revision_notice', ['version' => $sph->published_version]) }}
                @else
                    {{ __('pm::sph.published_notice', ['version' => $sph->published_version, 'date' => $sph->published_at?->format('d M Y H:i')]) }}
                @endif
            </span>
        @endif
        @if ($project->proposal)
            <button
                class="btn secondary sm ml-auto"
                type="button"
                wire:click="$dispatch('confirm-dialog', {
                    title: '{{ __('pm::sph.import_title') }}',
                    message: '{{ __('pm::sph.import_confirm') }}',
                    confirmColor: 'warning',
                    icon: 'fa-solid fa-file-import',
                    wireMethod: 'importFromProposal',
                    wireParams: [],
                    wireComponent: '{{ $this->getId() }}'
                })"
            ><span class="ph ph-download-simple mr-1"></span>{{ __('pm::sph.import_from_proposal') }}</button>
        @endif
    </div>

    <div class="mb-4 flex flex-wrap gap-1 border-b border-gray-200 dark:border-gray-700">
        @foreach ($tabs as $key => $label)
            <button
                class="-mb-px border-b-2 px-4 py-2 text-sm"
                type="button"
                x-on:click="tab = '{{ $key }}'"
                x-bind:class="tab === '{{ $key }}' ? 'border-brand-500 font-semibold' : 'border-transparent'"
            >
                {{ $label }}
                @if (!empty($tabErrors[$key]))
                    <span class="ml-1 inline-block h-2 w-2 rounded-full bg-red-500"></span>
                @endif
            </button>
        @endforeach
    </div>

    <div class="mb-24">
        @foreach (array_keys($tabs) as $key)
            <div
                x-show="tab === '{{ $key }}'"
                x-cloak
            >
                @if ($key === 'timeline')
                    @include('pm::proposal.tabs.timeline')
                @else
                    @include('pm::sph.tabs.' . $key)
                @endif
            </div>
        @endforeach
    </div>

    <!-- Fixed Action Buttons -->
    <div
        class="card z-9 fixed bottom-0 right-0 m-5 flex gap-2 shadow-gray-700"
        x-data="pmSphPreview"
    >
        <button
            class="btn secondary"
            type="button"
            x-on:click="openPdf"
        ><span class="ph ph-file-pdf mr-1"></span>{{ __('pm::sph.preview_pdf') }}</button>
        <button
            class="btn secondary"
            type="button"
            x-on:click="downloadDocx"
        ><span class="ph ph-file-doc mr-1"></span>{{ __('pm::sph.preview_docx') }}</button>
        <button
            class="btn secondary"
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            wire:target="save"
        >{{ __('pm::sph.save') }}</button>
        <button
            class="btn primary"
            type="button"
            wire:loading.attr="disabled"
            wire:target="publish"
            wire:click="$dispatch('confirm-dialog', {
                title: '{{ __('pm::sph.publish_title') }}',
                message: '{{ __('pm::sph.publish_confirm') }}',
                confirmColor: 'primary',
                icon: 'fa-solid fa-paper-plane',
                wireMethod: 'publish',
                wireParams: [],
                wireComponent: '{{ $this->getId() }}'
            })"
        >
            <span
                class="ph ph-spinner syn-spin mr-1"
                wire:loading
                wire:target="publish"
            ></span>
            {{ __('pm::sph.publish') }}
        </button>
        <a
            class="btn secondary"
            href="{{ backend_route('pm.project.detail', ['id' => $project->id]) }}"
            wire:navigate
        >{{ __('pm::project.cancel') }}</a>
    </div>
</div>

<script nonce="{{ csp_nonce() }}">
    (() => {
        const register = () => {
            Alpine.data('pmSphPreview', () => ({
                // Open the tab inside the click (a user gesture); a window.open() after the
                // Livewire round-trip would be popup-blocked.
                openPdf() {
                    const win = window.open('', '_blank');
                    this.$wire.preview('pdf').then((url) => {
                        if (url) {
                            win.location.href = url;
                        } else {
                            win.close();
                        }
                    });
                },
                downloadDocx() {
                    this.$wire.preview('docx').then((url) => {
                        if (url) {
                            window.location.href = url;
                        }
                    });
                },
            }));
        };
        document.addEventListener('alpine:init', register);
        if (window.Alpine) {
            register();
        }
    })();
</script>
