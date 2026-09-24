@props([
    'project' => null,
    'type' => null,
    'title' => '',
    'badge' => '',
    'readonly' => false,
])

<x-synapse-panel
    :title="$title"
    collapsible
>
    <div class="syn-panel-body">
        {{ $slot }}
    </div>
</x-synapse-panel>
