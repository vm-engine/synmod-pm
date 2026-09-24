<div class="flex h-full flex-col">
    <!-- Modal Header (Sticky) -->
    <div class="syn-modal-header shrink-0">
        <h3 class="syn-modal-title">
            @if ($form->issuer)
                {{ __('pm::issuer.edit_title') }}
            @else
                {{ __('pm::issuer.create_title') }}
            @endif
        </h3>
        <button
            class="syn-modal-close"
            type="button"
            @click="$dispatch('close-modal-issuer-form')"
        >
            <span class="ph ph-x text-xl"></span>
        </button>
    </div>

    <!-- Modal Body (Scrollable) -->
    <div class="syn-modal-body max-h-[700px] flex-1 space-y-4 overflow-y-auto">
        <!-- Name -->
        <div class="form-box required">
            <label for="name">{{ __('pm::labels.name') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.name') has-error @enderror"
                    id="name"
                    name="name"
                    type="text"
                    wire:model="form.name"
                    placeholder="{{ __('pm::issuer.name_placeholder') }}"
                >
            </div>
            @error('form.name')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Header Image Upload -->
        <div class="form-box {{ $form->issuer ? '' : 'required' }}">
            <label>{{ __('pm::issuer.header_image') }}</label>

            @if ($form->existing_header_image && !$form->header_image)
                <!-- Show existing image -->
                <div class="mb-4">
                    <img
                        class="h-auto max-w-full rounded-lg shadow-md"
                        src="{{ Storage::url($form->existing_header_image) }}"
                        alt="Current header"
                    >
                    <p class="mt-2 text-sm text-gray-500">{{ __('pm::issuer.current_image') }}</p>
                </div>
            @endif

            <x-synapse-drag-n-drop
                name="header_image"
                wire-model="form.header_image"
                accept="image/png,image/jpeg,image/webp"
                :max-size="5120"
                :preview="true"
                :label="__('pm::issuer.drop_image_here')"
                :description="__('pm::issuer.image_description')"
                :browse-text="__('pm::labels.browse')"
                :error="$errors->first('form.header_image')"
            />

            @error('form.header_image')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tax ID Name -->
        <div class="form-box required">
            <label for="tax_id_name">{{ __('pm::issuer.tax_id_name') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.tax_id_name') has-error @enderror"
                    id="tax_id_name"
                    name="tax_id_name"
                    type="text"
                    wire:model="form.tax_id_name"
                    placeholder="{{ __('pm::issuer.tax_id_name_placeholder') }}"
                >
            </div>
            @error('form.tax_id_name')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Tax ID Address -->
        <div class="form-box required">
            <label for="tax_id_address">{{ __('pm::issuer.tax_id_address') }}</label>
            <div class="input-group">
                <textarea
                    class="form-input @error('form.tax_id_address') has-error @enderror"
                    id="tax_id_address"
                    name="tax_id_address"
                    rows="3"
                    wire:model="form.tax_id_address"
                    placeholder="{{ __('pm::issuer.tax_id_address_placeholder') }}"
                ></textarea>
            </div>
            @error('form.tax_id_address')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Tax ID Number -->
        <div class="form-box required">
            <label for="tax_id_number">{{ __('pm::issuer.tax_id_number') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.tax_id_number') has-error @enderror font-mono"
                    id="tax_id_number"
                    name="tax_id_number"
                    type="text"
                    wire:model="form.tax_id_number"
                    placeholder="1234567890123456"
                    maxlength="16"
                >
            </div>
            <p class="form-help">{{ __('pm::issuer.tax_id_format') }}</p>
            @error('form.tax_id_number')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <!-- Modal Footer (Sticky) -->
    <div class="syn-modal-footer shrink-0">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-issuer-form')"
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
