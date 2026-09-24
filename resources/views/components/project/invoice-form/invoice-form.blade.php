<div class="flex h-full flex-col">
    <!-- Modal Header (Sticky) -->
    <div class="syn-modal-header shrink-0">
        <h3 class="syn-modal-title">
            @if ($form->invoice)
                {{ __('pm::invoice.edit_title') }}
            @else
                {{ __('pm::invoice.create_title') }}
            @endif
        </h3>
        <button
            class="syn-modal-close"
            type="button"
            @click="$dispatch('close-modal-invoice-form')"
        >
            <span class="ph ph-x text-xl"></span>
        </button>
    </div>

    <!-- Modal Body (Scrollable) -->
    <div class="syn-modal-body max-h-[700px] flex-1 space-y-4 overflow-y-auto">
        <!-- From External SPK -->
        <div class="form-box">
            <label
                class="flex cursor-pointer select-none items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400"
            >
                <input
                    class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                    type="checkbox"
                    wire:model.live="form.is_external_spk"
                >
                <span>{{ __('pm::invoice.external_spk') }}</span>
            </label>
        </div>

        <!-- SPK Number -->
        <div class="form-box">
            <label for="spk_number">{{ __('pm::invoice.spk_number') }}</label>
            <div class="input-group">
                @if ($form->is_external_spk)
                    <input
                        class="form-input @error('form.spk_number') has-error @enderror font-mono"
                        id="spk_number"
                        type="text"
                        wire:model="form.spk_number"
                        placeholder="{{ __('pm::invoice.spk_number_placeholder') }}"
                    >
                @else
                    <input
                        class="form-input bg-gray-100 dark:bg-gray-700"
                        id="spk_number"
                        type="text"
                        value="{{ $form->spk_number }}"
                        readonly
                        disabled
                    >
                @endif
            </div>
            @if ($form->is_external_spk)
                <p class="form-help">{{ __('pm::invoice.spk_number_manual_hint') }}</p>
                @error('form.spk_number')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            @else
                <p class="form-help">{{ __('pm::invoice.spk_number_hint') }}</p>
            @endif
        </div>

        <!-- Invoice Number -->
        <div class="form-box required">
            <label for="invoice_number">{{ __('pm::invoice.invoice_number') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.invoice_number') has-error @enderror font-mono"
                    id="invoice_number"
                    type="text"
                    wire:model="form.invoice_number"
                    placeholder="{{ __('pm::invoice.invoice_number_placeholder') }}"
                >
            </div>
            @error('form.invoice_number')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Currency -->
        <div
            class="form-box required"
            wire:key="currency-select-{{ $formNonce }}"
        >
            <label for="currency">{{ __('pm::invoice.currency') }}</label>
            @php
                $currencyLocked = $form->invoice && $form->invoice->exists && $form->invoice->items()->exists();
            @endphp
            @if ($currencyLocked)
                <input
                    class="form-input bg-gray-100 dark:bg-gray-700"
                    type="text"
                    value="{{ $form->currency }}"
                    readonly
                    disabled
                >
                <p class="form-help">{{ __('pm::invoice.validation.currency_locked') }}</p>
            @else
                <x-synapse-adv-select
                    wire-model="form.currency"
                    :live="true"
                    :options="$this->currencyOptions"
                    placeholder="{{ __('pm::invoice.select_currency') }}"
                    :error="$errors->first('form.currency')"
                />
            @endif
            @error('form.currency')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Recipient -->
        <div class="form-box required">
            <label for="recipient">{{ __('pm::invoice.recipient') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.recipient') has-error @enderror"
                    id="recipient"
                    type="text"
                    wire:model="form.recipient"
                    placeholder="{{ __('pm::invoice.recipient_placeholder') }}"
                >
            </div>
            @error('form.recipient')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address -->
        <div class="form-box required">
            <label for="address">{{ __('pm::invoice.address') }}</label>
            <div class="input-group">
                <textarea
                    class="form-input @error('form.address') has-error @enderror"
                    id="address"
                    rows="3"
                    wire:model="form.address"
                    placeholder="{{ __('pm::invoice.address_placeholder') }}"
                ></textarea>
            </div>
            @error('form.address')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date Row -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <!-- Issue Date -->
            <div class="form-box required">
                <label for="issue_date">{{ __('pm::invoice.issue_date') }}</label>
                <x-synapse-datepicker
                    id="issue_date"
                    name="issue_date"
                    wire:model="form.issue_date"
                    :error="$errors->first('form.issue_date')"
                />
                @error('form.issue_date')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="form-box required">
                <label for="due_date">{{ __('pm::invoice.due_date') }}</label>
                <x-synapse-datepicker
                    id="due_date"
                    name="due_date"
                    wire:model="form.due_date"
                    :error="$errors->first('form.due_date')"
                />
                @error('form.due_date')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Attention -->
        <div class="form-box">
            <label for="attention">{{ __('pm::invoice.attention') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.attention') has-error @enderror"
                    id="attention"
                    type="text"
                    wire:model="form.attention"
                    placeholder="{{ __('pm::invoice.attention_placeholder') }}"
                >
            </div>
            @error('form.attention')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="form-box">
            <label for="notes">{{ __('pm::invoice.notes') }}</label>
            <div class="input-group">
                <textarea
                    class="form-input @error('form.notes') has-error @enderror"
                    id="notes"
                    rows="3"
                    wire:model="form.notes"
                    placeholder="{{ __('pm::invoice.notes_placeholder') }}"
                ></textarea>
            </div>
            @error('form.notes')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Issuer -->
        <div
            class="form-box required"
            wire:key="issuer-select-{{ $formNonce }}"
        >
            <label for="issuer_id">{{ __('pm::invoice.issuer') }}</label>
            <x-synapse-adv-select
                wire-model="form.issuer_id"
                :live="true"
                :options="$this->issuers"
                placeholder="{{ __('pm::invoice.select_issuer') }}"
                :error="$errors->first('form.issuer_id')"
            />
            @error('form.issuer_id')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bank Account -->
        <div
            class="form-box required"
            wire:key="bank-select-{{ $formNonce }}"
        >
            <label for="bank_account_id">{{ __('pm::invoice.bank_account') }}</label>
            <x-synapse-adv-select
                wire-model="form.bank_account_id"
                :live="true"
                :options="$this->bankAccounts"
                placeholder="{{ __('pm::invoice.select_bank_account') }}"
                :error="$errors->first('form.bank_account_id')"
            />
            @error('form.bank_account_id')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Signature Toggle -->
        <div class="form-box">
            <label
                class="flex cursor-pointer select-none items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400"
            >
                <input
                    class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
                    id="has_signature"
                    name="has_signature"
                    type="checkbox"
                    wire:model.live="form.has_signature"
                >
                <span>{{ __('pm::invoice.has_signature') }}</span>
            </label>
        </div>

        <!-- Signature Dropdown (shown when has_signature is true) -->
        @if ($form->has_signature)
            <div
                class="form-box required"
                wire:key="signature-select-{{ $formNonce }}"
            >
                <label for="signature_id">{{ __('pm::invoice.signature') }}</label>
                <x-synapse-adv-select
                    wire-model="form.signature_id"
                    :live="true"
                    :options="$this->signatures"
                    placeholder="{{ __('pm::invoice.select_signature') }}"
                    :error="$errors->first('form.signature_id')"
                />
                @error('form.signature_id')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
        @endif
    </div>

    <!-- Modal Footer (Sticky) -->
    <div class="syn-modal-footer shrink-0">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-invoice-form')"
        >
            <span class="ph ph-x mr-1"></span>
            {{ __('pm::labels.cancel') }}
        </button>
        <button
            class="btn primary"
            type="button"
            wire:click="save"
        >
            <span
                class="fa-solid fa-save mr-1"
                wire:loading.class.remove="fa-save"
                wire:loading.class="fa-spinner fa-spin"
                wire:target="save"
            ></span>
            {{ __('pm::labels.save') }}
        </button>
    </div>
</div>
