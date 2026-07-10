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
     * Array of English number words (0-19).
     */
    protected static array $englishOnes = [
        'Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen',
    ];

    /**
     * Array of English tens words, indexed by tens digit (2-9).
     */
    protected static array $englishTens = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety',
    ];

    /**
     * English scale words, indexed by thousand-group position.
     */
    protected static array $englishScales = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];

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
     * Convert a number to English text representation.
     */
    public static function convertEnglish(float|int $number): string
    {
        $number = abs((int) $number);

        if ($number === 0) {
            return 'Zero';
        }

        $groups = [];
        while ($number > 0) {
            $groups[] = $number % 1000;
            $number = (int) ($number / 1000);
        }

        $parts = [];
        foreach (array_reverse($groups, true) as $scale => $group) {
            if ($group === 0) {
                continue;
            }

            $words = self::convertEnglishGroup($group);
            $parts[] = trim($words.' '.self::$englishScales[$scale]);
        }

        return implode(' ', $parts);
    }

    /**
     * Convert a 1-999 group into English words (no scale suffix).
     */
    protected static function convertEnglishGroup(int $number): string
    {
        $words = '';

        if ($number >= 100) {
            $words .= self::$englishOnes[(int) ($number / 100)].' Hundred';
            $number %= 100;
            if ($number > 0) {
                $words .= ' ';
            }
        }

        if ($number >= 20) {
            $words .= self::$englishTens[(int) ($number / 10)];
            $units = $number % 10;
            if ($units > 0) {
                $words .= ' '.self::$englishOnes[$units];
            }
        } elseif ($number > 0) {
            $words .= self::$englishOnes[$number];
        }

        return $words;
    }

    /**
     * Convert a number to Indonesian currency text (with "Rupiah" suffix).
     * Pass $lang = 'en' for English number words (the currency name stays
     * "Rupiah" in both languages, matching local invoicing convention).
     */
    public static function rupiah(float|int $number, string $lang = 'id'): string
    {
        return self::amountInWords($number, 'Rupiah', 'Rupiah', $lang);
    }

    /**
     * Convert a number to English currency text (with "Rupiah" suffix).
     */
    public static function rupiahEnglish(float|int $number): string
    {
        return self::amountInWords($number, 'Rupiah', 'Rupiah', 'en');
    }

    /**
     * Convert a number to amount-in-words text for an arbitrary currency,
     * e.g. amountInWords(100, 'Dollar', 'Dollars', 'en') => "One Hundred Dollars".
     * Indonesian doesn't inflect nouns for plurality, so $currencyNamePlural
     * is only ever used when $lang is 'en'.
     */
    public static function amountInWords(float|int $number, string $currencyName, string $currencyNamePlural, string $lang = 'id'): string
    {
        $absNumber = abs((int) $number);
        $name = ($lang === 'en' && $absNumber !== 1) ? $currencyNamePlural : $currencyName;

        if ($absNumber === 0) {
            return ($lang === 'en' ? 'Zero' : 'Nol').' '.$name;
        }

        $words = $lang === 'en' ? self::convertEnglish($number) : self::convert($number);

        return $words.' '.$name;
    }
}
