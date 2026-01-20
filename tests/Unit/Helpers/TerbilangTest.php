<?php

declare(strict_types=1);

use VmEngine\Pm\Helpers\Terbilang;

test('converts zero to Nol Rupiah', function () {
    expect(Terbilang::rupiah(0))->toBe('Nol Rupiah');
});

test('converts single digits correctly', function () {
    expect(Terbilang::convert(1))->toBe('Satu');
    expect(Terbilang::convert(5))->toBe('Lima');
    expect(Terbilang::convert(9))->toBe('Sembilan');
});

test('converts numbers 10-11 correctly', function () {
    expect(Terbilang::convert(10))->toBe('Sepuluh');
    expect(Terbilang::convert(11))->toBe('Sebelas');
});

test('converts teens correctly', function () {
    expect(Terbilang::convert(12))->toBe('Dua Belas');
    expect(Terbilang::convert(15))->toBe('Lima Belas');
    expect(Terbilang::convert(19))->toBe('Sembilan Belas');
});

test('converts tens correctly', function () {
    expect(Terbilang::convert(20))->toBe('Dua Puluh');
    expect(Terbilang::convert(30))->toBe('Tiga Puluh');
    expect(Terbilang::convert(50))->toBe('Lima Puluh');
    expect(Terbilang::convert(99))->toBe('Sembilan Puluh Sembilan');
});

test('converts hundreds correctly', function () {
    expect(Terbilang::convert(100))->toBe('Seratus');
    expect(Terbilang::convert(200))->toBe('Dua Ratus');
    expect(Terbilang::convert(555))->toBe('Lima Ratus Lima Puluh Lima');
    expect(Terbilang::convert(999))->toBe('Sembilan Ratus Sembilan Puluh Sembilan');
});

test('converts thousands correctly', function () {
    expect(Terbilang::convert(1000))->toBe('Seribu');
    expect(Terbilang::convert(2000))->toBe('Dua Ribu');
    expect(Terbilang::convert(10000))->toBe('Sepuluh Ribu');
    expect(Terbilang::convert(99999))->toBe('Sembilan Puluh Sembilan Ribu Sembilan Ratus Sembilan Puluh Sembilan');
});

test('converts millions correctly', function () {
    expect(Terbilang::convert(1000000))->toBe('Satu Juta');
    expect(Terbilang::convert(5000000))->toBe('Lima Juta');
    expect(Terbilang::convert(1500000))->toBe('Satu Juta Lima Ratus Ribu');
});

test('converts billions correctly', function () {
    expect(Terbilang::convert(1000000000))->toBe('Satu Miliar');
    expect(Terbilang::convert(2500000000))->toBe('Dua Miliar Lima Ratus Juta');
});

test('converts trillions correctly', function () {
    expect(Terbilang::convert(1000000000000))->toBe('Satu Triliun');
});

test('converts complex number correctly', function () {
    expect(Terbilang::convert(1234567))->toBe('Satu Juta Dua Ratus Tiga Puluh Empat Ribu Lima Ratus Enam Puluh Tujuh');
});

test('rupiah method adds Rupiah suffix', function () {
    expect(Terbilang::rupiah(1000))->toBe('Seribu Rupiah');
    expect(Terbilang::rupiah(50000))->toBe('Lima Puluh Ribu Rupiah');
    expect(Terbilang::rupiah(1000000))->toBe('Satu Juta Rupiah');
});

test('handles negative numbers as absolute values', function () {
    expect(Terbilang::convert(-100))->toBe('Seratus');
    expect(Terbilang::rupiah(-50000))->toBe('Lima Puluh Ribu Rupiah');
});

test('handles float by converting to integer', function () {
    expect(Terbilang::convert(100.75))->toBe('Seratus');
    expect(Terbilang::rupiah(1500.99))->toBe('Seribu Lima Ratus Rupiah');
});

test('typical invoice amounts', function () {
    expect(Terbilang::rupiah(1110000))->toBe('Satu Juta Seratus Sepuluh Ribu Rupiah');
    expect(Terbilang::rupiah(5500000))->toBe('Lima Juta Lima Ratus Ribu Rupiah');
    expect(Terbilang::rupiah(10000000))->toBe('Sepuluh Juta Rupiah');
});
