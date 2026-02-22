<div class="flex h-full flex-col">
    {{-- Modal Header (Sticky) --}}
    <div class="flex shrink-0 items-center justify-between border-b border-gray-200 p-5 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            @if ($form->bankAccount)
                {{ __('pm::bank_account.edit_title') }}
            @else
                {{ __('pm::bank_account.create_title') }}
            @endif
        </h3>
        <button
            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            type="button"
            @click="$dispatch('close-modal-bank-account-form')"
        >
            <span class="fa-solid fa-times text-xl"></span>
        </button>
    </div>

    {{-- Modal Body (Scrollable) --}}
    <div class="max-h-[700px] flex-1 space-y-4 overflow-y-auto p-6">
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

        {{-- Bank Name --}}
        <div class="form-box required">
            <label for="bank_name">{{ __('pm::bank_account.bank_name') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.bank_name') has-error @enderror"
                    id="bank_name"
                    name="bank_name"
                    type="text"
                    wire:model="form.bank_name"
                    placeholder="{{ __('pm::bank_account.bank_name_placeholder') }}"
                >
            </div>
            @error('form.bank_name')
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
    <div class="flex shrink-0 items-center justify-end gap-3 border-t border-gray-200 p-5 dark:border-gray-700">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-bank-account-form')"
        >
            <span class="fa-solid fa-times mr-1"></span>
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
