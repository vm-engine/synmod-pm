@include('pm::proposal.partials.tab-errors', ['key' => 'general'])
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="card-body grid grid-cols-1 gap-4">
            <div class="form-box required">
                <label for="spk-date">{{ __('pm::spk.field_date') }}</label>
                <x-synapse-datepicker
                    id="spk-date"
                    name="spk_date"
                    wire:model="spkDate"
                />
                @error('spkDate')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-box required">
                <label for="spk-title">{{ __('pm::spk.field_title') }}</label>
                <input
                    class="form-input @error('title') has-error @enderror"
                    id="spk-title"
                    type="text"
                    wire:model="title"
                />
                <p class="form-help">{{ __('pm::spk.title_hint') }}</p>
                @error('title')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <h3 class="font-semibold">{{ __('pm::spk.party2') }}</h3>
            <div class="form-box required">
                <label>{{ __('pm::spk.field_issuer') }}</label>
                <x-synapse-adv-select
                    wire-model="issuerId"
                    :options="$this->issuers"
                    placeholder="{{ __('pm::spk.select_issuer') }}"
                    :error="$errors->first('issuerId')"
                />
            </div>
            <div class="form-box required">
                <label>{{ __('pm::spk.field_signature') }}</label>
                <x-synapse-adv-select
                    wire-model="signatureId"
                    :options="$this->signatures"
                    placeholder="{{ __('pm::spk.select_signature') }}"
                    :error="$errors->first('signatureId')"
                />
            </div>
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="hasSignature"
                />
                {{ __('pm::spk.field_has_signature') }}
            </label>
            <div class="form-box required">
                <label for="spk-party2-address">{{ __('pm::spk.field_address') }}</label>
                <textarea
                    class="form-input"
                    id="spk-party2-address"
                    rows="3"
                    wire:model="party2Address"
                ></textarea>
            </div>
            <div class="form-box">
                <label for="spk-party2-description">{{ __('pm::spk.field_description') }}</label>
                <input
                    class="form-input"
                    id="spk-party2-description"
                    type="text"
                    wire:model="party2Description"
                />
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body grid grid-cols-1 gap-4">
            <h3 class="font-semibold">{{ __('pm::spk.party1') }}</h3>
            <div class="form-box required">
                <label for="spk-party1-company">{{ __('pm::spk.field_company') }}</label>
                <input
                    class="form-input"
                    id="spk-party1-company"
                    type="text"
                    wire:model="party1Company"
                />
            </div>
            <div class="form-box required">
                <label for="spk-party1-address">{{ __('pm::spk.field_address') }}</label>
                <textarea
                    class="form-input"
                    id="spk-party1-address"
                    rows="3"
                    wire:model="party1Address"
                ></textarea>
            </div>
            <div class="form-box required">
                <label for="spk-party1-representative">{{ __('pm::spk.field_representative') }}</label>
                <input
                    class="form-input"
                    id="spk-party1-representative"
                    type="text"
                    wire:model="party1Representative"
                />
            </div>
            <div class="form-box required">
                <label for="spk-party1-position">{{ __('pm::spk.field_position') }}</label>
                <input
                    class="form-input"
                    id="spk-party1-position"
                    type="text"
                    wire:model="party1Position"
                />
            </div>
            <div class="form-box">
                <label for="spk-party1-description">{{ __('pm::spk.field_description') }}</label>
                <input
                    class="form-input"
                    id="spk-party1-description"
                    type="text"
                    wire:model="party1Description"
                />
            </div>
        </div>
    </div>
</div>
