<?php

declare(strict_types=1);

namespace VmEngine\Pm\Helpers;

class Terbilang
{
    /**
     * Array of Indonesian number words.
     */
    protected static array $words = [
        '',
        'Satu',
        'Dua',
        'Tiga',
        'Empat',
        'Lima',
        'Enam',
        'Tujuh',
        'Delapan',
        'Sembilan',
        'Sepuluh',
        'Sebelas',
    ];

    /**
     * Convert a number to Indonesian text representation.
     */
    public static function convert(float|int $number): string
    {
        $number = abs((int) $number);

        if ($number < 12) {
            return self::$words[$number];
        }

        if ($number < 20) {
            return self::$words[$number - 10].' Belas';
        }

        if ($number < 100) {
            $tens = (int) ($number / 10);
            $units = $number % 10;

            return self::$words[$tens].' Puluh'.($units > 0 ? ' '.self::$words[$units] : '');
        }

        if ($number < 200) {
            return 'Seratus'.($number > 100 ? ' '.self::convert($number - 100) : '');
        }

        if ($number < 1000) {
            $hundreds = (int) ($number / 100);
            $remainder = $number % 100;

            return self::$words[$hundreds].' Ratus'.($remainder > 0 ? ' '.self::convert($remainder) : '');
        }

        if ($number < 2000) {
            return 'Seribu'.($number > 1000 ? ' '.self::convert($number - 1000) : '');
        }

        if ($number < 1000000) {
            $thousands = (int) ($number / 1000);
            $remainder = $number % 1000;

            return self::convert($thousands).' Ribu'.($remainder > 0 ? ' '.self::convert($remainder) : '');
        }

        if ($number < 1000000000) {
            $millions = (int) ($number / 1000000);
            $remainder = $number % 1000000;

            return self::convert($millions).' Juta'.($remainder > 0 ? ' '.self::convert($remainder) : '');
        }

        if ($number < 1000000000000) {
            $billions = (int) ($number / 1000000000);
            $remainder = $number % 1000000000;

            return self::convert($billions).' Miliar'.($remainder > 0 ? ' '.self::convert($remainder) : '');
        }

        $trillions = (int) ($number / 1000000000000);
        $remainder = $number % 1000000000000;

        return self::convert($trillions).' Triliun'.($remainder > 0 ? ' '.self::convert($remainder) : '');
    }

    /**
     * Convert a number to Indonesian currency text (with "Rupiah" suffix).
     */
    public static function rupiah(float|int $number): string
    {
        if ($number == 0) {
            return 'Nol Rupiah';
        }

        return self::convert($number).' Rupiah';
    }
}
