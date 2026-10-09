<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="card">
        <div class="card-body space-y-4">
            <div class="form-box">
                <label for="scope-goal">{{ __('pm::proposal.goal') }}</label>
                <textarea
                    class="form-input"
                    id="scope-goal"
                    rows="6"
                    wire:model="scope.goal"
                ></textarea>
            </div>
            <div class="form-box">
                <label>{{ __('pm::proposal.menu') }}</label>
                <div class="space-y-2">
                    @foreach ($scope['menu'] ?? [] as $i => $node)
                        <div
                            class="space-y-2 rounded-lg border border-gray-200 p-3 dark:border-gray-700"
                            wire:key="menu-{{ $node['id'] }}"
                        >
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-[1fr_2fr_auto]">
                                <input
                                    class="form-input"
                                    type="text"
                                    placeholder="{{ __('pm::proposal.name') }}"
                                    wire:model="scope.menu.{{ $i }}.name"
                                />
                                <input
                                    class="form-input"
                                    type="text"
                                    placeholder="{{ __('pm::proposal.description') }}"
                                    wire:model="scope.menu.{{ $i }}.description"
                                />
                                @include('pm::proposal.partials.row-actions', [
                                    'list' => 'scope.menu',
                                    'index' => $i,
                                    'count' => count($scope['menu']),
                                ])
                            </div>
                            @foreach ($node['children'] ?? [] as $c => $child)
                                <div
                                    class="ml-8 grid grid-cols-1 gap-2 sm:grid-cols-[1fr_2fr_auto]"
                                    wire:key="menu-{{ $node['id'] }}-{{ $child['id'] }}"
                                >
                                    <input
                                        class="form-input"
                                        type="text"
                                        wire:model="scope.menu.{{ $i }}.children.{{ $c }}.name"
                                    />
                                    <input
                                        class="form-input"
                                        type="text"
                                        wire:model="scope.menu.{{ $i }}.children.{{ $c }}.description"
                                    />
                                    <button
                                        class="btn-icon danger"
                                        type="button"
                                        wire:click="removeChild('scope.menu', {{ $i }}, {{ $c }})"
                                    ><span class="ph ph-trash"></span></button>
                                </div>
                            @endforeach
                            <button
                                class="btn-link ml-8 text-sm"
                                type="button"
                                wire:click="addChild('scope.menu', {{ $i }})"
                            ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_child') }}</button>
                        </div>
                    @endforeach
                    <button
                        class="btn secondary"
                        type="button"
                        wire:click="addRow('scope.menu')"
                    ><span class="ph ph-plus mr-1"></span>{{ __('pm::proposal.add_menu') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('pm::proposal.mockups') }}</h3>
        </div>
        <div class="card-body space-y-3">
            @foreach ($scope['mockups'] ?? [] as $i => $mockup)
                <div
                    class="grid grid-cols-[auto_1fr_auto] items-center gap-3"
                    wire:key="mockup-{{ $mockup['path'] }}"
                >
                    <span class="ph ph-image text-2xl"></span>
                    <input
                        class="form-input"
                        type="text"
                        placeholder="{{ __('pm::proposal.mockup_caption') }}"
                        wire:model="scope.mockups.{{ $i }}.caption"
                    />
                    <button
                        class="btn-icon danger"
                        type="button"
                        wire:click="removeMockup({{ $i }})"
                    ><span class="ph ph-trash"></span></button>
                </div>
            @endforeach
            <x-synapse-file-drop
                wire-model="mockupUploads"
                multiple
                :allowed-types="['jpg', 'jpeg', 'png']"
                :max-size-kb="5120"
            />
            @error('mockupUploads.*')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
