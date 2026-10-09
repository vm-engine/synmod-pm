@include('pm::proposal.partials.tab-errors', ['key' => 'attachments'])
<div class="card">
    <div class="card-body space-y-4">
        <p class="form-help">{{ __('pm::spk.attachments_hint') }}</p>
        @foreach ($attachments as $i => $attachment)
            <div
                class="flex items-center gap-4 rounded-lg border border-gray-200 p-3 dark:border-gray-700"
                wire:key="attachment-{{ md5($attachment['path']) }}"
            >
                <span class="ph ph-image text-2xl"></span>
                <div class="form-box flex-1">
                    <label>{{ __('pm::spk.attachment_title') }}</label>
                    <input
                        class="form-input"
                        type="text"
                        placeholder="Struktur Organisasi"
                        wire:model="attachments.{{ $i }}.title"
                    />
                </div>
                <button
                    class="btn-icon danger"
                    type="button"
                    wire:click="removeAttachment({{ $i }})"
                ><span class="ph ph-trash"></span></button>
            </div>
        @endforeach
        <x-synapse-file-drop
            wire-model="attachmentUploads"
            multiple
            :allowed-types="['jpg', 'jpeg', 'png']"
            :max-size-kb="5120"
        />
        @error('attachmentUploads.*')
            <p class="form-error-message">{{ $message }}</p>
        @enderror
    </div>
</div>
