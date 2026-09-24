<div class="flex h-full flex-col">
    {{-- Modal Header (Sticky) --}}
    <div class="syn-modal-header shrink-0">
        <h3 class="syn-modal-title">
            @if ($form->bankAccount)
                {{ __('pm::bank_account.edit_title') }}
            @else
                {{ __('pm::bank_account.create_title') }}
            @endif
        </h3>
        <button
            class="syn-modal-close"
            type="button"
            @click="$dispatch('close-modal-bank-account-form')"
        >
            <span class="ph ph-x text-xl"></span>
        </button>
    </div>

    {{-- Modal Body (Scrollable) --}}
    <div class="syn-modal-body max-h-[700px] flex-1 space-y-4 overflow-y-auto">
        {{-- Account Name --}}
        <div class="form-box required">
            <label for="account_name">{{ __('pm::labels.name') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.name') has-error @enderror"
                    id="account_name"
                    name="name"
                    type="text"
                    wire:model="form.name"
                    placeholder="{{ __('pm::bank_account.name_placeholder') }}"
                >
            </div>
            @error('form.name')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Account Number --}}
        <div class="form-box required">
            <label for="account_number">{{ __('pm::bank_account.number') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.number') has-error @enderror font-mono"
                    id="account_number"
                    name="number"
                    type="text"
                    wire:model="form.number"
                    placeholder="{{ __('pm::bank_account.number_placeholder') }}"
                >
            </div>
            @error('form.number')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Bank Name (with autocomplete) --}}
        {{--
            Filtering is done fully client-side against a local `query` variable, so
            typing never triggers a Livewire commit/morph. The dropdown carries
            `wire:ignore` so Livewire never morphs Alpine's x-for generated <li>
            nodes (mirrors the adv-select component); without it, morph-vs-Alpine
            reconciliation floods the console with expression errors.
        --}}
        <div
            class="form-box required"
            x-data="pmBankAccountAutocomplete()"
            {{-- .capture: this form renders inside <x-synapse-modal>, whose content
                 box calls stopPropagation() on click; without .capture this
                 bubble-phase document listener would never fire. --}}
            @click.away.capture="open = false"
        >
            <label for="bank_name">{{ __('pm::bank_account.bank_name') }}</label>
            <div class="input-group relative">
                <input
                    class="form-input @error('form.bank_name') has-error @enderror"
                    id="bank_name"
                    name="bank_name"
                    type="text"
                    autocomplete="off"
                    wire:model="form.bank_name"
                    @input="handleBankNameInput($event.target.value)"
                    @focus="open = true"
                    placeholder="{{ __('pm::bank_account.bank_name_placeholder') }}"
                >
                <ul
                    wire:ignore
                    x-show="open && filtered.length > 0"
                    x-cloak
                    class="absolute z-20 mt-1 max-h-56 w-full overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800"
                >
                    <template
                        x-for="s in filtered"
                        :key="s.bank_name"
                    >
                        <li
                            class="cursor-pointer px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                            @click="pick(s)"
                        >
                            <span
                                class="font-medium"
                                x-text="s.bank_name"
                            ></span>
                            <span
                                class="ml-2 font-mono text-xs text-gray-500"
                                x-show="s.swift_code"
                                x-text="s.swift_code"
                            ></span>
                        </li>
                    </template>
                </ul>
            </div>
            @error('form.bank_name')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- SWIFT Code --}}
        <div class="form-box">
            <label for="swift_code">{{ __('pm::bank_account.swift_code') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.swift_code') has-error @enderror font-mono uppercase"
                    id="swift_code"
                    name="swift_code"
                    type="text"
                    wire:model="form.swift_code"
                    placeholder="{{ __('pm::bank_account.swift_code_placeholder') }}"
                >
            </div>
            @error('form.swift_code')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Bank Logo Upload --}}
        <div class="form-box {{ $form->bankAccount ? '' : 'required' }}">
            <label>{{ __('pm::bank_account.bank_logo') }}</label>

            @if ($form->existing_bank_logo && !$form->bank_logo)
                {{-- Show existing logo --}}
                <div class="mb-4">
                    <img
                        class="h-auto max-w-full rounded-lg shadow-md"
                        src="{{ Storage::url($form->existing_bank_logo) }}"
                        alt="Current logo"
                    >
                    <p class="mt-2 text-sm text-gray-500">{{ __('pm::bank_account.current_logo') }}</p>
                </div>
            @endif

            <x-synapse-drag-n-drop
                name="bank_logo"
                wire-model="form.bank_logo"
                accept="image/png,image/jpeg,image/webp"
                :max-size="5120"
                :preview="true"
                :label="__('pm::bank_account.drop_logo_here')"
                :description="__('pm::bank_account.logo_description')"
                :browse-text="__('pm::labels.browse')"
                :error="$errors->first('form.bank_logo')"
            />

            @error('form.bank_logo')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Modal Footer (Sticky) --}}
    <div class="syn-modal-footer shrink-0">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-bank-account-form')"
        >
            <span class="ph ph-x mr-1"></span>
            {{ __('pm::labels.cancel') }}
        </button>
        <button
            class="btn primary"
            type="button"
            wire:click="save"
        >
            <span
                class="fa-solid fa-save mr-1"
                wire:loading.class.remove="fa-save"
                wire:loading.class="fa-spinner fa-spin"
                wire:target="save"
            ></span>
            {{ __('pm::labels.save') }}
        </button>
    </div>
</div>

<script nonce="{{ csp_nonce() }}">
    (() => {
        const register = () => {
            Alpine.data('pmBankAccountAutocomplete', () => ({
                open: false,
                query: '',
                suggestions: @js($this->bankNameSuggestions),
                init() {
                    this.query = (this.$wire?.get('form.bank_name')) || '';
                },
                handleBankNameInput(value) {
                    this.query = value;
                    this.open = true;
                },
                get filtered() {
                    const q = (this.query || '').toLowerCase();
                    if (!q) return this.suggestions;
                    return this.suggestions.filter(s => s.bank_name.toLowerCase().includes(q));
                },
                pick(s) {
                    this.query = s.bank_name;
                    this.$wire.set('form.bank_name', s.bank_name);
                    if (s.swift_code) {
                        this.$wire.set('form.swift_code', s.swift_code);
                    }
                    this.open = false;
                }
            }));
        };
        document.addEventListener('alpine:init', register);
        if (window.Alpine) {
            register();
        }
    })();
</script>
