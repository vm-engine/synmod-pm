@foreach ($blocks as $block)
    @php
        $html = implode(
            '',
            array_map(function (array $run): string {
                $text = e($run['text']);
                $text = $run['bold'] ? '<strong>' . $text . '</strong>' : $text;

                return $run['italic'] ? '<em>' . $text . '</em>' : $text;
            }, $block['runs']),
        );
        $marker = $block['marker'] !== '' ? e($block['marker']) . '&nbsp;' : '';
    @endphp
    <div
        class="block"
        style="padding-left: {{ $block['level'] * 14 }}pt; text-indent: {{ $block['marker'] !== '' ? -10 : 0 }}pt;"
    >{!! $marker . $html !!}</div>
@endforeach
