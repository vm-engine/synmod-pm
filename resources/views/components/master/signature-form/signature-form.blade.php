<div class="flex h-full flex-col">
    {{-- Modal Header (Sticky) --}}
    <div class="syn-modal-header shrink-0">
        <h3 class="syn-modal-title">
            @if ($form->signature)
                {{ __('pm::signature.edit_title') }}
            @else
                {{ __('pm::signature.create_title') }}
            @endif
        </h3>
        <button
            class="syn-modal-close"
            type="button"
            @click="$dispatch('close-modal-signature-form')"
        >
            <span class="ph ph-x text-xl"></span>
        </button>
    </div>

    {{-- Modal Body (Scrollable) --}}
    <div class="syn-modal-body max-h-[700px] flex-1 space-y-4 overflow-y-auto">
        {{-- Name --}}
        <div class="form-box required">
            <label for="signature_name">{{ __('pm::labels.name') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.name') has-error @enderror"
                    id="signature_name"
                    name="name"
                    type="text"
                    wire:model="form.name"
                    placeholder="{{ __('pm::signature.name_placeholder') }}"
                >
            </div>
            @error('form.name')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Position --}}
        <div class="form-box required">
            <label for="signature_position">{{ __('pm::signature.position') }}</label>
            <div class="input-group">
                <input
                    class="form-input @error('form.position') has-error @enderror"
                    id="signature_position"
                    name="position"
                    type="text"
                    wire:model="form.position"
                    placeholder="{{ __('pm::signature.position_placeholder') }}"
                >
            </div>
            @error('form.position')
                <p class="form-error-message">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Signature File Upload --}}
        <div class="form-box {{ $form->signature ? '' : 'required' }}">
            <label>{{ __('pm::signature.file') }}</label>

            @if ($form->existing_file && !$form->file)
                {{-- Show existing file --}}
                <div class="mb-4">
                    <img
                        class="h-auto max-w-full rounded-lg shadow-md"
                        src="{{ Storage::url($form->existing_file) }}"
                        alt="Current signature"
                    >
                    <p class="mt-2 text-sm text-gray-500">{{ __('pm::signature.current_file') }}</p>
                </div>
            @endif

            <x-synapse-drag-n-drop
                name="signature_file"
                wire-model="form.file"
                accept="image/png,image/jpeg,image/webp"
                :max-size="5120"
                :preview="true"
                :label="__('pm::signature.drop_file_here')"
                :description="__('pm::signature.file_description')"
                :browse-text="__('pm::labels.browse')"
                :error="$errors->first('form.file')"
            />

            @error('form.file')
                <p class="form-error-message">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Modal Footer (Sticky) --}}
    <div class="syn-modal-footer shrink-0">
        <button
            class="btn secondary"
            type="button"
            @click="$dispatch('close-modal-signature-form')"
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
