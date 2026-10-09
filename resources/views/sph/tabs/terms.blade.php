@include('pm::proposal.partials.tab-errors', ['key' => 'terms'])
@include('pm::proposal.partials.tab-errors', ['key' => 'timeline'])
<div class="card">
    <div class="card-body grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div class="space-y-3">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="paymentEnabled"
                />
                {{ __('pm::sph.field_payment_enabled') }}
            </label>
            @if ($paymentEnabled)
                <div class="form-box">
                    <label for="sph-dp">{{ __('pm::sph.field_dp_percentage') }}</label>
                    <input
                        class="form-input"
                        id="sph-dp"
                        type="number"
                        min="1"
                        max="99"
                        step="0.01"
                        wire:model.live.blur="dpPercentage"
                    />
                    <p class="form-help">{{ $this->paymentPreview }}</p>
                </div>
            @endif
        </div>
        <div class="space-y-3">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="showTimelineSummary"
                />
                {{ __('pm::sph.field_show_timeline_summary') }}
            </label>
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model.live="showTimeplan"
                />
                {{ __('pm::sph.field_show_timeplan') }}
            </label>
            <p class="form-help">{{ __('pm::sph.working_days', ['days' => $this->workingDays]) }}</p>
        </div>
    </div>
</div>
