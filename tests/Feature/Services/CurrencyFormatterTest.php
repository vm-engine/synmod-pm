<?php

declare(strict_types=1);

use VmEngine\Pm\Services\CurrencyFormatter;

it('lists configured currency codes', function () {
    expect(app(CurrencyFormatter::class)->codes())->toContain('IDR', 'USD');
});

it('reports taxability per currency', function () {
    $f = app(CurrencyFormatter::class);
    expect($f->isTaxable('IDR'))->toBeTrue();
    expect($f->isTaxable('USD'))->toBeFalse();
});

it('falls back to a taxable IDR-like definition for unknown currency', function () {
    expect(app(CurrencyFormatter::class)->isTaxable('ZZZ'))->toBeTrue();
});

it('formats IDR with dot thousands and no decimals', function () {
    expect(app(CurrencyFormatter::class)->format(1500000, 'IDR'))->toBe('Rp 1.500.000');
});

it('formats USD with comma thousands and two decimals', function () {
    expect(app(CurrencyFormatter::class)->format(1500.5, 'USD'))->toBe('$ 1,500.50');
});

it('parses IDR input by stripping thousands separators', function () {
    expect(app(CurrencyFormatter::class)->parse('1.500.000', 'IDR'))->toBe(1500000.0);
});

it('parses USD input honoring comma thousands and dot decimal', function () {
    expect(app(CurrencyFormatter::class)->parse('1,500.50', 'USD'))->toBe(1500.5);
});
