@include('pm::proposal.partials.tab-errors', ['key' => 'extra'])
<div class="card">
    <div class="card-body space-y-4">
        <p class="form-help">{{ __('pm::spk.extra_hint') }}</p>
        @foreach ($extraPasal as $i => $row)
            <div
                class="space-y-3 rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                wire:key="extra-{{ $row['id'] }}"
            >
                <div class="flex items-end gap-4">
                    <span class="pb-2 font-semibold">Pasal {{ $this->pasalNumbers['extra.' . $i] ?? '' }}</span>
                    <div class="form-box flex-1">
                        <label>{{ __('pm::spk.extra_title') }}</label>
                        <input
                            class="form-input"
                            type="text"
                            placeholder="Garansi"
                            wire:model="extraPasal.{{ $i }}.title"
                        />
                    </div>
                    @include('pm::proposal.partials.row-actions', [
                        'list' => 'extraPasal',
                        'index' => $i,
                        'count' => count($extraPasal),
                    ])
                </div>
                <div class="form-box">
                    <label>{{ __('pm::spk.extra_body') }}</label>
                    <textarea
                        class="form-input font-mono"
                        rows="6"
                        wire:model="extraPasal.{{ $i }}.body"
                    ></textarea>
                    <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
                </div>
            </div>
        @endforeach
        <button
            class="btn secondary"
            type="button"
            wire:click="addRow('extraPasal')"
        ><span class="ph ph-plus mr-1"></span>{{ __('pm::spk.add_extra') }}</button>
    </div>
</div>
