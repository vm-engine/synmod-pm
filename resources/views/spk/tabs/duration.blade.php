@include('pm::proposal.partials.tab-errors', ['key' => 'duration'])
<div class="space-y-6">
    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['duration'], 'title' => 'Jangka Waktu']) }}
            </h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="form-box required">
                    <label for="spk-work-duration">{{ __('pm::spk.field_work_duration') }}</label>
                    <input
                        class="form-input @error('workDuration') has-error @enderror"
                        id="spk-work-duration"
                        type="number"
                        min="1"
                        wire:model="workDuration"
                    />
                    @error('workDuration')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-box">
                    <label for="spk-work-unit">&nbsp;</label>
                    <select
                        class="form-input"
                        id="spk-work-unit"
                        wire:model="workDurationUnit"
                    >
                        <option value="hari kerja">hari kerja</option>
                        <option value="hari kalender">hari kalender</option>
                        <option value="bulan">bulan</option>
                    </select>
                </div>
                <div class="form-box sm:col-span-2">
                    <label for="spk-maintenance">{{ __('pm::spk.field_maintenance_months') }}</label>
                    <input
                        class="form-input @error('maintenanceMonths') has-error @enderror"
                        id="spk-maintenance"
                        type="number"
                        min="0"
                        wire:model="maintenanceMonths"
                    />
                </div>
            </div>
            <div class="form-box">
                <label for="spk-work-start">{{ __('pm::spk.field_work_start') }}</label>
                <input
                    class="form-input"
                    id="spk-work-start"
                    type="text"
                    wire:model="workStartText"
                />
            </div>
            <div class="form-box">
                <label for="spk-duration-notes">{{ __('pm::spk.field_duration_notes') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-duration-notes"
                    rows="4"
                    wire:model="durationNotes"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.continuation_hint') }}</p>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body space-y-4">
            <h3 class="font-semibold">
                {{ __('pm::spk.pasal_heading', ['number' => $this->pasalNumbers['obligations'], 'title' => 'Hak dan Kewajiban']) }}
            </h3>
            <div class="form-box">
                <label for="spk-obligations-first">{{ __('pm::spk.field_obligations_first') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-obligations-first"
                    rows="8"
                    wire:model="obligationsFirst"
                ></textarea>
            </div>
            <div class="form-box">
                <label for="spk-obligations-second">{{ __('pm::spk.field_obligations_second') }}</label>
                <textarea
                    class="form-input font-mono"
                    id="spk-obligations-second"
                    rows="8"
                    wire:model="obligationsSecond"
                ></textarea>
                <p class="form-help">{{ __('pm::spk.markdown_hint') }}</p>
            </div>
        </div>
    </div>
</div>
