@if (!empty($tabErrors[$key]))
    <x-synapse-alert
        type="danger"
        :dismissible="false"
    >
        <ul class="list-inside list-disc">
            @foreach ($tabErrors[$key] as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </x-synapse-alert>
@endif
