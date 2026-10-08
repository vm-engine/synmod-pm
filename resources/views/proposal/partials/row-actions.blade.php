<div class="flex gap-1">
    <button
        class="btn-icon"
        type="button"
        title="{{ __('pm::proposal.move_up') }}"
        wire:click="moveRow('{{ $list }}', {{ $index }}, -1)"
        @disabled($index === 0)
    ><span class="ph ph-arrow-up"></span></button>
    <button
        class="btn-icon"
        type="button"
        title="{{ __('pm::proposal.move_down') }}"
        wire:click="moveRow('{{ $list }}', {{ $index }}, 1)"
        @disabled($index === $count - 1)
    ><span class="ph ph-arrow-down"></span></button>
    <button
        class="btn-icon danger"
        type="button"
        title="{{ __('pm::proposal.remove') }}"
        wire:click="removeRow('{{ $list }}', {{ $index }})"
    ><span class="ph ph-trash"></span></button>
</div>
