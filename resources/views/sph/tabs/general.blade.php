@include('pm::proposal.partials.tab-errors', ['key' => 'general'])
<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="card-body grid grid-cols-1 gap-4">
            <div class="form-box required">
                <label for="sph-date">{{ __('pm::sph.field_date') }}</label>
                <x-synapse-datepicker
                    id="sph-date"
                    name="sph_date"
                    wire:model="sphDate"
                />
                @error('sphDate')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-box required">
                <label for="sph-subject">{{ __('pm::sph.field_subject') }}</label>
                <input
                    class="form-input @error('subject') has-error @enderror"
                    id="sph-subject"
                    type="text"
                    wire:model="subject"
                />
                @error('subject')
                    <p class="form-error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-box">
                <label for="sph-attachment-label">{{ __('pm::sph.field_attachment_label') }}</label>
                <input
                    class="form-input"
                    id="sph-attachment-label"
                    type="text"
                    wire:model="attachmentLabel"
                />
            </div>
            <div class="form-box required">
                <label for="sph-need">{{ __('pm::sph.field_need') }}</label>
                <input
                    class="form-input"
                    id="sph-need"
                    type="text"
                    wire:model="need"
                />
            </div>
            <div class="form-box required">
                <label>{{ __('pm::sph.field_issuer') }}</label>
                <x-synapse-adv-select
                    wire-model="issuerId"
                    :options="$this->issuers"
                    placeholder="{{ __('pm::sph.select_issuer') }}"
                    :error="$errors->first('issuerId')"
                />
            </div>
            <div class="form-box required">
                <label>{{ __('pm::sph.field_signature') }}</label>
                <x-synapse-adv-select
                    wire-model="signatureId"
                    :options="$this->signatures"
                    placeholder="{{ __('pm::sph.select_signature') }}"
                    :error="$errors->first('signatureId')"
                />
            </div>
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="hasSignature"
                />
                {{ __('pm::sph.field_has_signature') }}
            </label>
        </div>
    </div>
    <div class="card">
        <div class="card-body grid grid-cols-1 gap-4">
            <div class="form-box required">
                <label for="sph-recipient-name">{{ __('pm::sph.field_recipient_name') }}</label>
                <input
                    class="form-input"
                    id="sph-recipient-name"
                    type="text"
                    wire:model="recipientName"
                />
            </div>
            <div class="form-box">
                <label for="sph-recipient-title">{{ __('pm::sph.field_recipient_title') }}</label>
                <input
                    class="form-input"
                    id="sph-recipient-title"
                    type="text"
                    wire:model="recipientTitle"
                />
            </div>
            <div class="form-box">
                <label for="sph-recipient-company">{{ __('pm::sph.field_recipient_company') }}</label>
                <input
                    class="form-input"
                    id="sph-recipient-company"
                    type="text"
                    wire:model="recipientCompany"
                />
            </div>
            <div class="form-box">
                <label for="sph-recipient-address">{{ __('pm::sph.field_recipient_address') }}</label>
                <textarea
                    class="form-input"
                    id="sph-recipient-address"
                    rows="3"
                    wire:model="recipientAddress"
                ></textarea>
            </div>
        </div>
    </div>
</div>
