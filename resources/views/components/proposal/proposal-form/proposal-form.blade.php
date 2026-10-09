<div
    class="relative overflow-y-auto"
    x-data="{ pageName: '{{ __('pm::proposal.page_title') }}', isHome: false, tab: 'general' }"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])
    <x-synapse-confirm-dialog />

    @if (!$this->proposal)
        <div class="card mx-auto max-w-xl">
            <div class="card-body space-y-4 text-center">
                <h3 class="card-title">{{ __('pm::proposal.create_heading') }}</h3>
                <p class="form-help">{{ __('pm::proposal.create_hint') }}</p>
                <div class="flex justify-center gap-3">
                    @foreach (\VmEngine\Pm\Enums\ProposalType::cases() as $option)
                        <button
                            class="btn primary"
                            type="button"
                            wire:click="create({{ $option->value }})"
                        >{{ $option->label() }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        @php
            $proposal = $this->proposal;
            $tabs = [
                'general' => __('pm::proposal.tab_general'),
                'scope' => __('pm::proposal.tab_scope'),
                'features' => __('pm::proposal.tab_features'),
                'costing' => __('pm::proposal.tab_costing'),
                'hosting' => __('pm::proposal.tab_hosting'),
                'timeline' => __('pm::proposal.tab_timeline'),
                'deliverables' => __('pm::proposal.tab_deliverables'),
            ];
            if ($type !== \VmEngine\Pm\Enums\ProposalType::Website->value) {
                unset($tabs['scope']);
            }
        @endphp

        <div class="mb-4 flex flex-wrap items-center gap-3">
            <x-synapse-badge :color="$proposal->isPublished() ? 'success' : 'gray'">{{ $proposal->status->label() }}</x-synapse-badge>
            <span class="font-mono">{{ $proposal->number ?? '—' }}</span>
            @if ($proposal->isPublished())
                <span class="form-help">
                    @if ($proposal->has_unpublished_changes)
                        {{ __('pm::proposal.revision_notice', ['version' => $proposal->published_version]) }}
                    @else
                        {{ __('pm::proposal.published_notice', ['version' => $proposal->published_version, 'date' => $proposal->published_at?->format('d M Y H:i')]) }}
                    @endif
                </span>
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
                    @include('pm::proposal.tabs.' . $key)
                </div>
            @endforeach
        </div>

        <!-- Fixed Action Buttons -->
        <div
            class="card z-9 fixed bottom-0 right-0 m-5 flex gap-2 shadow-gray-700"
            x-data="pmProposalPreview"
        >
            <button
                class="btn secondary"
                type="button"
                x-on:click="openPdf"
            ><span class="ph ph-file-pdf mr-1"></span>{{ __('pm::proposal.preview_pdf') }}</button>
            <button
                class="btn secondary"
                type="button"
                x-on:click="downloadPptx"
            ><span class="ph ph-presentation mr-1"></span>{{ __('pm::proposal.preview_pptx') }}</button>
            <button
                class="btn secondary"
                type="button"
                wire:click="save"
                wire:loading.attr="disabled"
                wire:target="save"
            >{{ __('pm::proposal.save') }}</button>
            <button
                class="btn primary"
                type="button"
                wire:loading.attr="disabled"
                wire:target="publish"
                wire:click="$dispatch('confirm-dialog', {
                    title: '{{ __('pm::proposal.publish_title') }}',
                    message: '{{ addslashes(__('pm::proposal.publish_confirm')) }}',
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
                {{ __('pm::proposal.publish') }}
            </button>
            <a
                class="btn secondary"
                href="{{ backend_route('pm.project.detail', ['id' => $project->id]) }}"
                wire:navigate
            >{{ __('pm::project.cancel') }}</a>
        </div>
    @endif
</div>

<script nonce="{{ csp_nonce() }}">
    (() => {
        const register = () => {
            Alpine.data('pmProposalPreview', () => ({
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
                // A download keeps the current page, so no new tab is needed.
                downloadPptx() {
                    this.$wire.preview('pptx').then((url) => {
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
