<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('pm::proposal.tab_deliverables') }}</h3>
        </div>
        <div class="card-body space-y-3">
            <p class="form-help">{{ __('pm::proposal.months_hint') }}</p>
            @foreach ($deliverables as $i => $row)
                <div
                    class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_1fr_auto]"
                    wire:key="deliverable-{{ $row['id'] }}"
                >
                    <input
                        class="form-input"
                        type="text"
                        placeholder="{{ __('pm::proposal.item') }}"
                        wire:model="deliverables.{{ $i }}.item"
                    />
                    <input
                        class="form-input"
                        type="text"
                        placeholder="{{ __('pm::proposal.description') }}"
                        wire:model="deliverables.{{ $i }}.description"
                    />
                    @include('pm::proposal.partials.row-actions', [
                        'list' => 'deliverables',
                        'index' => $i,
                        'count' => count($deliverables),
                    ])
                </div>
            @endforeach
            <button
                class="btn secondary"
                type="button"
                wire:click="addRow('deliverables')"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_deliverable') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('pm::proposal.after_sales') }}</h3>
        </div>
        <div class="card-body space-y-3">
            @foreach ($afterSales as $i => $row)
                <div
                    class="grid grid-cols-[1fr_auto] gap-2"
                    wire:key="after-sales-{{ $row['id'] }}"
                >
                    <input
                        class="form-input"
                        type="text"
                        wire:model="afterSales.{{ $i }}.label"
                    />
                    @include('pm::proposal.partials.row-actions', [
                        'list' => 'afterSales',
                        'index' => $i,
                        'count' => count($afterSales),
                    ])
                </div>
            @endforeach
            <button
                class="btn secondary"
                type="button"
                wire:click="addRow('afterSales')"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_after_sales') }}</button>
        </div>
    </div>
</div>
