<div x-data="{ pageName: '{{ __('pm::proposal.feature_items_title') }}', isHome: false }">
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])
    <x-synapse-confirm-dialog />
    <div class="card">
        <div class="card-body space-y-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_2fr_auto]">
                <input
                    class="form-input"
                    type="text"
                    placeholder="{{ __('pm::proposal.name') }}"
                    wire:model="newName"
                />
                <input
                    class="form-input"
                    type="text"
                    placeholder="{{ __('pm::proposal.description') }}"
                    wire:model="newDescription"
                />
                <button
                    class="btn primary"
                    type="button"
                    wire:click="store"
                >{{ __('pm::proposal.save') }}</button>
            </div>
            @error('newName')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-20">{{ __('pm::proposal.sort') }}</th>
                        <th class="w-64">{{ __('pm::proposal.name') }}</th>
                        <th>{{ __('pm::proposal.description') }}</th>
                        <th class="w-28">{{ __('pm::proposal.default_checked') }}</th>
                        <th class="w-20">{{ __('pm::proposal.active') }}</th>
                        <th class="w-16"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->items as $item)
                        <tr wire:key="feature-item-{{ $item->id }}">
                            <td><input
                                    class="form-input"
                                    type="number"
                                    value="{{ $item->sort }}"
                                    wire:change="updateSort({{ $item->id }}, $event.target.value)"
                                /></td>
                            <td><input
                                    class="form-input"
                                    type="text"
                                    value="{{ $item->name }}"
                                    wire:change="updateItem({{ $item->id }}, 'name', $event.target.value)"
                                /></td>
                            <td>
                                <textarea
                                    class="form-input"
                                    rows="2"
                                    wire:change="updateItem({{ $item->id }}, 'description', $event.target.value)"
                                >{{ $item->description }}</textarea>
                            </td>
                            <td class="text-center"><input
                                    type="checkbox"
                                    @checked($item->default_checked)
                                    wire:click="toggle({{ $item->id }}, 'default_checked')"
                                /></td>
                            <td class="text-center"><input
                                    type="checkbox"
                                    @checked($item->active)
                                    wire:click="toggle({{ $item->id }}, 'active')"
                                /></td>
                            <td>
                                <button
                                    class="btn-icon danger"
                                    type="button"
                                    wire:click="$dispatch('confirm-dialog', {
                                        title: '{{ __('pm::proposal.remove') }}',
                                        message: {{ \Illuminate\Support\Js::from($item->name) }},
                                        confirmColor: 'danger',
                                        icon: 'fa-solid fa-trash',
                                        wireMethod: 'delete',
                                        wireParams: ['{{ $item->delete_token }}'],
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
