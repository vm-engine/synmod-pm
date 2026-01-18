<div class="flex h-full flex-col">
    <!-- Modal Header (Sticky) -->
    <div class="flex shrink-0 items-center justify-between border-b border-gray-200 p-5 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            @if ($form->invoice)
                {{ __('pm::invoice.edit_title') }}
            @else
                {{ __('pm::invoice.create_title') }}
            @endif
        </h3>
        <button
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            type="button"
            @click="$dispatch('close-modal-invoice-form')"
        >
            <span class="fa-solid fa-times text-xl"></span>
        </button>
    </div>

    <!-- Modal Body (Scrollable) -->
    <div class="max-h-[700px] flex-1 space-y-4 overflow-y-auto p-6">
        <!-- SPK Number -->
        <div class="form-box">
            <label for="spk_number">{{ __('pm::invoice.spk_number') }}</label>
            <div class="input-group">
                <input
                    class="form-input bg-gray-100 dark:bg-gray-700"
                    id="spk_number"
                    type="text"
                    value="{{ $form->spk_number }}"
                    readonly
                    disabled
                >
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('pm::invoice.spk_number_hint') }}</p>
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
        <div class="form-box required" wire:key="issuer-select-{{ $form->invoice?->id ?? 'new' }}">
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
        <div class="form-box required" wire:key="bank-select-{{ $form->invoice?->id ?? 'new' }}">
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
            <label class="flex cursor-pointer select-none items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400">
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
            <div class="form-box required" wire:key="signature-select-{{ $form->invoice?->id ?? 'new' }}">
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
    <div class="flex shrink-0 items-center justify-end gap-3 border-t border-gray-200 p-5 dark:border-gray-700">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-invoice-form')"
        >
            <span class="fa-solid fa-times mr-1"></span>
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
