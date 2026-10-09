<?php

declare(strict_types=1);

use VmEngine\Pm\Services\Sph\MarkdownBlocks;

function mdRun(string $text, bool $bold = false, bool $italic = false): array
{
    return ['text' => $text, 'bold' => $bold, 'italic' => $italic];
}

test('empty input gives no blocks', function () {
    expect(MarkdownBlocks::parse("  \n"))->toBe([]);
});

test('paragraphs keep bold and italic runs', function () {
    expect(MarkdownBlocks::parse("Hello **big** *world*\nsecond line"))->toBe([
        ['level' => 0, 'marker' => '', 'runs' => [
            mdRun('Hello '), mdRun('big', bold: true), mdRun(' '), mdRun('world', italic: true), mdRun(' '), mdRun('second line'),
        ]],
    ]);
});

test('nested bullet lists get levels and bullet markers', function () {
    expect(MarkdownBlocks::parse("- auth_events\n  - login\n  - logout\n- **activity_logs** table"))->toBe([
        ['level' => 1, 'marker' => '•', 'runs' => [mdRun('auth_events')]],
        ['level' => 2, 'marker' => '•', 'runs' => [mdRun('login')]],
        ['level' => 2, 'marker' => '•', 'runs' => [mdRun('logout')]],
        ['level' => 1, 'marker' => '•', 'runs' => [mdRun('activity_logs', bold: true), mdRun(' table')]],
    ]);
});

test('ordered lists number from their start', function () {
    expect(array_column(MarkdownBlocks::parse("3. Block\n4. Unblock"), 'marker'))->toBe(['3.', '4.']);
});

test('a second paragraph inside a list item continues without a marker', function () {
    $blocks = MarkdownBlocks::parse("- Email OTP\n\n  SMTP disediakan klien.\n- TOTP");

    expect(array_map(fn (array $b): array => [$b['level'], $b['marker']], $blocks))
        ->toBe([[1, '•'], [1, ''], [1, '•']]);
});

test('unsupported syntax degrades to plain text', function () {
    $blocks = MarkdownBlocks::parse("# Judul\n\n<b>raw</b>\n\n[link](https://x.test)");

    expect(array_map(fn (array $b): string => implode('', array_column($b['runs'], 'text')), $blocks))
        ->toBe(['Judul', '<b>raw</b>', 'link']);
});
