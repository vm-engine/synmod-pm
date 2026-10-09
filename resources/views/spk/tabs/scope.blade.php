@include('pm::proposal.partials.tab-errors', ['key' => 'scope'])
<div class="space-y-6">
    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['asas'], 'title' => 'Asas Kerja Sama']) }}
            </h3>
            <div class="form-box">
                <label for="spk-asas">{{ __('pm::spk.field_asas') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-asas"
                    rows="4"
                    wire:model="asas"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['pokok'], 'title' => 'Pokok Perjanjian']) }}
            </h3>
            <div class="form-box required">
                <label for="spk-scope">{{ __('pm::spk.field_scope') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-scope"
                    rows="8"
                    wire:model="scope"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
            </div>
            <div class="form-box">
                <label for="spk-reference">{{ __('pm::spk.field_reference_document') }}</label>
                <input
                    class="form-input"
                    id="spk-reference"
                    type="text"
                    wire:model="referenceDocument"
                />
                <p class="form-help">{{ __('pm::spk.reference_hint') }}</p>
            </div>
        </div>
    </div>
</div>
