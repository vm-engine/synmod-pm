<?php

declare(strict_types=1);

require_once __DIR__.'/../Sph/Fixtures.php';

/** Plain text of a block. */
function spkText(array $block): string
{
    return implode('', array_column($block['runs'], 'text'));
}

/** All text of a Pasal (blocks and table cells). */
function spkPasalText(array $pasal): string
{
    $text = '';
    foreach ($pasal['parts'] as $part) {
        $text .= $part['type'] === 'blocks'
            ? implode("\n", array_map(fn (array $b): string => $b['marker'].' '.spkText($b), $part['blocks']))
            : implode("\n", array_map(fn (array $r): string => $r[0].': '.$r[1], $part['rows']));
        $text .= "\n";
    }

    return $text;
}
