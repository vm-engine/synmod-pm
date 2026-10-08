@include('pm::proposal.partials.tab-errors', ['key' => 'features'])
<div class="card">
    <div class="card-body space-y-3">
        @if ($type === \VmEngine\Pm\Enums\ProposalType::Website->value)
            @foreach ($features as $i => $feature)
                <div
                    class="grid grid-cols-1 items-start gap-2 rounded-lg border border-gray-200 p-3 sm:grid-cols-[auto_1fr_2fr_auto] dark:border-gray-700"
                    wire:key="feature-{{ $feature['id'] }}"
                >
                    <input
                        class="mt-3"
                        type="checkbox"
                        wire:model="features.{{ $i }}.checked"
                    />
                    <input
                        class="form-input"
                        type="text"
                        placeholder="{{ __('pm::proposal.name') }}"
                        wire:model="features.{{ $i }}.name"
                    />
                    <textarea
                        class="form-input"
                        rows="2"
                        placeholder="{{ __('pm::proposal.description') }}"
                        wire:model="features.{{ $i }}.description"
                    ></textarea>
                    @include('pm::proposal.partials.row-actions', [
                        'list' => 'features',
                        'index' => $i,
                        'count' => count($features),
                    ])
                </div>
            @endforeach
            <button
                class="btn secondary"
                type="button"
                wire:click="addCustomFeature"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_custom_feature') }}</button>
        @else
            @foreach ($features as $i => $module)
                <div
                    class="space-y-2 rounded-lg border border-gray-200 p-3 dark:border-gray-700"
                    wire:key="module-{{ $module['id'] }}"
                >
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_2fr_auto]">
                        <input
                            class="form-input font-semibold"
                            type="text"
                            placeholder="{{ __('pm::proposal.name') }}"
                            wire:model="features.{{ $i }}.name"
                        />
                        <input
                            class="form-input"
                            type="text"
                            placeholder="{{ __('pm::proposal.description') }}"
                            wire:model="features.{{ $i }}.description"
                        />
                        @include('pm::proposal.partials.row-actions', [
                            'list' => 'features',
                            'index' => $i,
                            'count' => count($features),
                        ])
                    </div>
                    @foreach ($module['children'] ?? [] as $c => $child)
                        <div
                            class="ml-8 grid grid-cols-1 gap-2 sm:grid-cols-[1fr_2fr_auto]"
                            wire:key="module-{{ $module['id'] }}-{{ $child['id'] }}"
                        >
                            <input
                                class="form-input"
                                type="text"
                                placeholder="{{ __('pm::proposal.name') }}"
                                wire:model="features.{{ $i }}.children.{{ $c }}.name"
                            />
                            <input
                                class="form-input"
                                type="text"
                                placeholder="{{ __('pm::proposal.description') }}"
                                wire:model="features.{{ $i }}.children.{{ $c }}.description"
                            />
                            <button
                                class="btn-icon danger"
                                type="button"
                                title="{{ __('pm::proposal.remove') }}"
                                wire:click="removeChild('features', {{ $i }}, {{ $c }})"
                            ><span class="ph ph-trash"></span></button>
                        </div>
                    @endforeach
                    <button
                        class="btn-link ml-8 text-sm"
                        type="button"
                        wire:click="addChild('features', {{ $i }})"
                    ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_child') }}</button>
                </div>
            @endforeach
            <button
                class="btn secondary"
                type="button"
                wire:click="addRow('features')"
            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_module') }}</button>
        @endif
    </div>
</div>
