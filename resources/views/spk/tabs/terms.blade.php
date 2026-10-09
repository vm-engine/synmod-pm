@include('pm::proposal.partials.tab-errors', ['key' => 'terms'])
<div class="space-y-6">
    @foreach (['confidentiality' => 'Kerahasiaan', 'dispute' => 'Penyelesaian Perselisihan'] as $field => $pasalTitle)
        <div
            class="card"
            wire:key="terms-{{ $field }}"
        >
            <div class="card-body space-y-4">
                <h3 class="font-semibold">
                    {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers[$field], 'title' => $pasalTitle]) }}
                </h3>
                <textarea
                    class="form-input font-mono"
                    rows="8"
                    aria-label="{{ $pasalTitle }}"
                    wire:model="{{ $field }}"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
            </div>
        </div>
    @endforeach

    <div class="card">
        <div class="card-body space-y-4">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="correspondenceEnabled"
                />
                {{ __('pm::spk.field_correspondence_enabled') }}
            </label>
            @if ($correspondenceEnabled)
                <h3 class="font-semibold">
                    {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['correspondence'], 'title' => 'Korespondensi']) }}
                </h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @foreach (['first' => __('pm::spk.party1'), 'second' => __('pm::spk.party2')] as $party => $partyLabel)
                        <div
                            class="space-y-3"
                            wire:key="contact-{{ $party }}"
                        >
                            <h4 class="font-semibold">{{ $partyLabel }}</h4>
                            @foreach (['name', 'address', 'phone', 'email'] as $contactField)
                                <div class="form-box">
                                    <label
                                        for="spk-contact-{{ $party }}-{{ $contactField }}">{{ __('pm::spk.contact_' . $contactField) }}</label>
                                    <input
                                        class="form-input"
                                        id="spk-contact-{{ $party }}-{{ $contactField }}"
                                        type="text"
                                        wire:model="contacts.{{ $party }}.{{ $contactField }}"
                                    />
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="form-box">
                    <label for="spk-correspondence-text">{{ __('pm::spk.field_correspondence_text') }}</label>
                    <textarea
                        class="form-input font-mono"
                        id="spk-correspondence-text"
                        rows="8"
                        wire:model="correspondenceText"
                    ></textarea>
                    <p class="form-help">{{ __('pm::spk.continuation_hint') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['misc'], 'title' => 'Lain-lain']) }}
            </h3>
            <textarea
                class="form-input font-mono"
                rows="6"
                aria-label="{{ __('pm::spk.field_misc') }}"
                wire:model="misc"
            ></textarea>
            <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
        </div>
    </div>
</div>
