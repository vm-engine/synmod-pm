<div class="card">
    <div class="card-body space-y-4">
        @foreach ($hosting as $i => $row)
            <div
                class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                wire:key="hosting-{{ $row['id'] }}"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="form-box">
                        <label>{{ __('pm::proposal.name') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            wire:model="hosting.{{ $i }}.name"
                        />
                    </div>
                    <div class="form-box">
                        <label>{{ __('pm::proposal.price') }}</label>
                        <x-synapse-currency
                            id="hosting-price-{{ $row['id'] }}"
                            name="hosting_price_{{ $i }}"
                            currency="Rp."
                            wire:model="hosting.{{ $i }}.price"
                        />
                    </div>
                    <div class="form-box">
                        <label>{{ __('pm::proposal.period') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            wire:model="hosting.{{ $i }}.period"
                        />
                    </div>
                    <div class="flex items-end justify-end">
                        @include('pm::proposal.partials.row-actions', [
                            'list' => 'hosting',
                            'index' => $i,
                            'count' => count($hosting),
                        ])
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-[1fr_auto]">
                    <div class="form-box">
                        <label>{{ __('pm::proposal.specs_one_per_line') }}</label>
                        <textarea
                            class="form-input"
                            rows="3"
                            wire:model="hosting.{{ $i }}.specs"
                        ></textarea>
                    </div>
                    <div class="flex flex-col justify-center gap-2">
                        <label class="flex items-center gap-2"><input
                                type="checkbox"
                                wire:model="hosting.{{ $i }}.optional"
                            /> {{ __('pm::proposal.optional') }}</label>
                        <label class="flex items-center gap-2"><input
                                type="checkbox"
                                wire:model="hosting.{{ $i }}.start_from"
                            /> {{ __('pm::proposal.start_from') }}</label>
                    </div>
                </div>
            </div>
        @endforeach
        <button
            class="btn secondary"
            type="button"
            wire:click="addRow('hosting')"
        ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_hosting') }}</button>
    </div>
</div>
