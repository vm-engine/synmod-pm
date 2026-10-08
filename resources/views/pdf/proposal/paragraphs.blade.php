@foreach ($paragraphs as $paragraph)
    @php
        $level = (int) ($paragraph['level'] ?? 0);
        $bullet = !empty($paragraph['bullet']);
        $size = ((int) ($paragraph['size'] ?? 1600)) / 100;
    @endphp
    <p
        class="para"
        style="font-size: {{ $size }}pt; margin-left: {{ $level * 36 + ($bullet ? 22.5 : 0) }}pt; {{ $bullet ? 'text-indent: -13pt;' : '' }}"
    >
        @if ($bullet)
            &bull;&nbsp;
        @endif
        @if (($paragraph['lead'] ?? '') !== '')
            <strong>{{ $paragraph['lead'] }}</strong>
        @endif
        @if (!empty($paragraph['bold']))
            <strong>{{ $paragraph['text'] }}</strong>
        @else
            {{ $paragraph['text'] }}
        @endif
    </p>
@endforeach
