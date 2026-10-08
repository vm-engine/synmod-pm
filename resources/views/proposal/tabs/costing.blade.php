@include('pm::proposal.partials.tab-errors', ['key' => 'costing'])
<div class="card">
    <div class="card-body space-y-4">
        @foreach ($costing as $i => $row)
            <div
                class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                wire:key="costing-{{ $row['id'] }}"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="form-box">
                        <label>{{ __('pm::proposal.section') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            wire:model="costing.{{ $i }}.section"
                        />
                    </div>
                    <div class="form-box">
                        <label>{{ __('pm::proposal.price') }}</label>
                        <x-synapse-currency
                            id="costing-price-{{ $row['id'] }}"
                            name="costing_price_{{ $i }}"
                            currency="Rp."
                            wire:model.live.blur="costing.{{ $i }}.price"
                        />
                    </div>
                    <div class="flex items-end justify-end">
                        @include('pm::proposal.partials.row-actions', [
                            'list' => 'costing',
                            'index' => $i,
                            'count' => count($costing),
                        ])
                    </div>
                </div>
                <div class="form-box">
                    <label>{{ __('pm::proposal.features_one_per_line') }}</label>
                    <textarea
                        class="form-input"
                        rows="4"
                        wire:model="costing.{{ $i }}.features"
                    ></textarea>
                </div>
            </div>
        @endforeach
        <div class="flex items-center justify-between">
            <button
                class="btn secondary"
                type="button"
                wire:click="addRow('costing')"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_section') }}</button>
            <div class="text-lg font-bold">
                {{ __('pm::proposal.grand_total') }}:
                {{ \VmEngine\Pm\Services\Proposal\ProposalDocumentData::money($this->grandTotal) }}
            </div>
        </div>
    </div>
</div>
