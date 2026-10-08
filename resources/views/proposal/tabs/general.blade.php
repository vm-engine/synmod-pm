@include('pm::proposal.partials.tab-errors', ['key' => 'general'])
<div class="card">
    <div class="card-body grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="form-box required">
            <label for="proposal-title">{{ __('pm::proposal.field_title') }}</label>
            <input
                class="form-input @error('title') has-error @enderror"
                id="proposal-title"
                type="text"
                wire:model="title"
            />
            @error('title')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-box">
            <label for="proposal-subject">{{ __('pm::proposal.field_subject') }}</label>
            <input
                class="form-input"
                id="proposal-subject"
                type="text"
                wire:model="subject"
            />
        </div>
        <div class="form-box required">
            <label for="proposal-date">{{ __('pm::proposal.field_date') }}</label>
            <x-synapse-datepicker
                id="proposal-date"
                name="proposal_date"
                wire:model="proposalDate"
            />
            @error('proposalDate')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-box">
            <label for="proposal-months">{{ __('pm::proposal.field_maintenance_months') }}</label>
            <input
                class="form-input"
                id="proposal-months"
                type="number"
                min="0"
                max="60"
                wire:model="maintenanceMonths"
            />
        </div>
        <div class="form-box">
            <label for="proposal-type">{{ __('pm::proposal.field_type') }}</label>
            <select
                class="form-input"
                id="proposal-type"
                x-data
                x-on:change="$dispatch('confirm-dialog', {
                    title: '{{ __('pm::proposal.change_type_title') }}',
                    message: '{{ __('pm::proposal.change_type_confirm') }}',
                    confirmColor: 'warning',
                    icon: 'fa-solid fa-shuffle',
                    wireMethod: 'changeType',
                    wireParams: [parseInt($event.target.value)],
                    wireComponent: '{{ $this->getId() }}'
                }); $event.target.value = '{{ $type }}'"
            >
                @foreach (\VmEngine\Pm\Enums\ProposalType::cases() as $option)
                    <option
                        value="{{ $option->value }}"
                        @selected($type === $option->value)
                    >{{ $option->label() }}</option>
                @endforeach
            </select>
        </div>
        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                wire:model="taxNote"
            />
            {{ __('pm::proposal.field_tax_note') }}
        </label>
    </div>
</div>
