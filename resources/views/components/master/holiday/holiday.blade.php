<div x-data="{ pageName: '{{ __('pm::proposal.holiday_title') }}', isHome: false }">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])
    <x-synapse-confirm-dialog />
    <div class="card">
        <div class="card-body space-y-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[200px_1fr_auto]">
                <div>
                    <x-synapse-datepicker
                        id="holiday-date"
                        name="holiday_date"
                        wire:model="newDate"
                    />
                    @error('newDate')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <input
                        class="form-input"
                        type="text"
                        placeholder="{{ __('pm::proposal.holiday_name') }}"
                        wire:model="newName"
                    />
                    @error('newName')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="btn primary"
                    type="button"
                    wire:click="store"
                >{{ __('pm::proposal.save') }}</button>
            </div>
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-48">{{ __('pm::proposal.holiday_date') }}</th>
                        <th>{{ __('pm::proposal.holiday_name') }}</th>
                        <th class="w-16"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->holidays as $holiday)
                        <tr wire:key="holiday-{{ $holiday->id }}">
                            <td>{{ $holiday->date->format('D d/m/Y') }}</td>
                            <td>{{ $holiday->name }}</td>
                            <td>
                                <button
                                    class="btn-icon danger"
                                    type="button"
                                    wire:click="$dispatch('confirm-dialog', {
                                        title: '{{ __('pm::proposal.remove') }}',
                                        message: {{ \Illuminate\Support\Js::from($holiday->name) }},
                                        confirmColor: 'danger',
                                        icon: 'fa-solid fa-trash',
                                        wireMethod: 'delete',
                                        wireParams: ['{{ $holiday->delete_token }}'],
                                        wireComponent: '{{ $this->getId() }}'
                                    })"
                                ><span class="ph ph-trash"></span></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
