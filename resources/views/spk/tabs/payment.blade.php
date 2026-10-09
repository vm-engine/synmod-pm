@include('pm::proposal.partials.tab-errors', ['key' => 'payment'])
@php
    $money = fn(float $amount): string => \VmEngine\Pm\Services\Proposal\ProposalDocumentData::money($amount);
    $sumOff = $installments !== [] && abs($this->grandTotal - $this->contractValueAmount) >= 1;
@endphp
<div class="space-y-6">
    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['payment'], 'title' => 'Nilai Kontrak dan Cara Pembayaran']) }}
            </h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="form-box required">
                    <label for="spk-contract-value">{{ __('pm::spk.field_contract_value') }}</label>
                    <x-synapse-currency
                        id="spk-contract-value"
                        name="contract_value"
                        currency="Rp."
                        wire:model.live.blur="contractValue"
                    />
                    <label class="mt-2 flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="taxNote"
                        />
                        {{ __('pm::spk.field_tax_note') }}
                    </label>
                </div>
                <div class="form-box required">
                    <label>{{ __('pm::spk.field_bank') }}</label>
                    <x-synapse-adv-select
                        wire-model="bankAccountId"
                        :options="$this->bankAccounts"
                        placeholder="{{ __('pm::spk.select_bank') }}"
                        :error="$errors->first('bankAccountId')"
                    />
                </div>
            </div>

            <h4 class="font-semibold">{{ __('pm::spk.installments') }}</h4>
            @foreach ($installments as $i => $row)
                <div
                    class="space-y-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    wire:key="installment-{{ $row['id'] }}"
                >
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="form-box sm:col-span-2">
                            <label>{{ __('pm::spk.installment_label') }}</label>
                            <input
                                class="form-input"
                                type="text"
                                wire:model="installments.{{ $i }}.label"
                            />
                        </div>
                        <div class="form-box">
                            <label>{{ __('pm::spk.installment_type') }}</label>
                            <select
                                class="form-input"
                                wire:model.live="installments.{{ $i }}.type"
                            >
                                <option value="percent">{{ __('pm::spk.installment_percent') }}</option>
                                <option value="amount">{{ __('pm::spk.installment_amount') }}</option>
                            </select>
                        </div>
                        <div class="form-box">
                            <label>{{ __('pm::spk.installment_value') }}</label>
                            @if ($row['type'] === 'percent')
                                <input
                                    class="form-input"
                                    type="number"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    wire:model.live.blur="installments.{{ $i }}.value"
                                />
                            @else
                                <x-synapse-currency
                                    id="installment-value-{{ $row['id'] }}"
                                    name="installment_value_{{ $i }}"
                                    currency="Rp."
                                    wire:model.live.blur="installments.{{ $i }}.value"
                                />
                            @endif
                        </div>
                        <div class="form-box">
                            <label>{{ __('pm::spk.installment_count') }}</label>
                            <input
                                class="form-input"
                                type="number"
                                min="1"
                                wire:model.live.blur="installments.{{ $i }}.count"
                            />
                        </div>
                        <div class="form-box sm:col-span-2">
                            <label>{{ __('pm::spk.installment_due') }}</label>
                            <input
                                class="form-input"
                                type="text"
                                wire:model="installments.{{ $i }}.due"
                            />
                        </div>
                        <div class="flex items-end justify-end">
                            @include('pm::proposal.partials.row-actions', [
                                'list' => 'installments',
                                'index' => $i,
                                'count' => count($installments),
                            ])
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="flex flex-wrap items-center justify-between gap-2">
                <button
                    class="btn secondary"
                    type="button"
                    wire:click="addRow('installments')"
                ><span class="ph ph-plus mr-1"></span>{{ __('pm::spk.add_installment') }}</button>
                <p class="{{ $sumOff ? 'font-semibold text-red-600' : 'form-help' }}">
                    {{ __('pm::spk.installments_total', ['total' => $money($this->grandTotal), 'value' => $money($this->contractValueAmount)]) }}
                </p>
            </div>

            <div class="form-box">
                <label for="spk-payment-notes">{{ __('pm::spk.field_payment_notes') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-payment-notes"
                    rows="4"
                    wire:model="paymentNotes"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.continuation_hint') }}</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body space-y-4">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="serverEnabled"
                />
                {{ __('pm::spk.field_server_enabled') }}
            </label>
            @if ($serverEnabled)
                <h3 class="font-semibold">
                    {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['server'], 'title' => 'Layanan Sewa Server dan Domain']) }}
                </h3>
                <div class="form-box">
                    <label for="spk-server">{{ __('pm::spk.field_server_text') }}</label>
                    <textarea
                        class="form-input font-mono"
                        id="spk-server"
                        rows="6"
                        wire:model="serverText"
                    ></textarea>
                    <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
