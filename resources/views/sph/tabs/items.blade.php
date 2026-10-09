@include('pm::proposal.partials.tab-errors', ['key' => 'items'])
<div class="card">
    <div class="card-body space-y-4">
        <div class="flex flex-wrap gap-6">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="showDuration"
                />
                {{ __('pm::sph.field_show_duration') }}
            </label>
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="taxNote"
                />
                {{ __('pm::sph.field_tax_note') }}
            </label>
        </div>
        @foreach ($items as $i => $row)
            <div
                class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                wire:key="item-{{ $row['id'] }}"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="form-box sm:col-span-2">
                        <label>{{ __('pm::sph.item_name') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            wire:model="items.{{ $i }}.name"
                        />
                    </div>
                    <div class="form-box">
                        <label>{{ __('pm::sph.item_price') }}</label>
                        <x-synapse-currency
                            id="item-price-{{ $row['id'] }}"
                            name="item_price_{{ $i }}"
                            currency="Rp."
                            wire:model.live.blur="items.{{ $i }}.price"
                        />
                    </div>
                    <div class="flex items-end justify-end">
                        @include('pm::proposal.partials.row-actions', [
                            'list' => 'items',
                            'index' => $i,
                            'count' => count($items),
                        ])
                    </div>
                </div>
                @if ($showDuration)
                    <div class="form-box">
                        <label>{{ __('pm::sph.item_duration') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            placeholder="15 Hari Kerja"
                            wire:model="items.{{ $i }}.duration"
                        />
                    </div>
                @endif
                <div class="form-box">
                    <label>{{ __('pm::sph.item_description') }}</label>
                    <textarea
                        class="form-input font-mono"
                        rows="6"
                        wire:model="items.{{ $i }}.description"
                    ></textarea>
                    <p class="form-help">{{ __('pm::sph.markdown_hint') }}</p>
                </div>
            </div>
        @endforeach
        <div class="flex items-center justify-between">
            <button
                class="btn secondary"
                type="button"
                wire:click="addRow('items')"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::sph.add_item') }}</button>
            <div class="text-lg font-bold">
                {{ __('pm::sph.grand_total') }}:
                {{ \VmEngine\Pm\Services\Proposal\ProposalDocumentData::money($this->grandTotal) }}
            </div>
        </div>
    </div>
</div>
