<div
    class="relative overflow-y-auto"
    x-data="{ pageName: '{{ __('pm::proposal.defaults_title') }}', isHome: false }"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <div class="mb-4 flex gap-2">
        @foreach (\VmEngine\Pm\Enums\ProposalType::cases() as $option)
            <button
                class="btn {{ $type === $option->value ? 'primary' : 'secondary' }}"
                type="button"
                wire:click="selectType({{ $option->value }})"
            >{{ $option->label() }}</button>
        @endforeach
    </div>

    <div class="mb-24 space-y-6">
        <h3 class="card-title">{{ __('pm::proposal.tab_costing') }}</h3>
        @include('pm::proposal.tabs.costing')
        <h3 class="card-title">{{ __('pm::proposal.tab_hosting') }}</h3>
        @include('pm::proposal.tabs.hosting')
        <h3 class="card-title">{{ __('pm::proposal.tab_timeline') }}</h3>
        <div class="card">
            <div class="card-body">@include('pm::proposal.partials.task-table')</div>
        </div>
        @include('pm::proposal.tabs.deliverables')
    </div>

    <div class="card z-9 fixed bottom-0 right-0 m-5 shadow-gray-700">
        <button
            class="btn primary"
            type="button"
            wire:click="save"
        >{{ __('pm::proposal.save') }}</button>
    </div>
</div>
