<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

class CurrencyFormatter
{
    /** @var array<string, array{symbol:string,decimals:int,thousands:string,decimal:string,taxable:bool,name?:string,name_plural?:string}> */
    private array $currencies;

    public function __construct()
    {
        $configured = config('synapps.modules.pm.currencies');

        $this->currencies = is_array($configured) && $configured !== []
            ? $configured
            : self::fallback();
    }

    /**
     * @return array<string, array{symbol:string,decimals:int,thousands:string,decimal:string,taxable:bool,name:string,name_plural:string}>
     */
    public static function fallback(): array
    {
        return [
            'IDR' => ['symbol' => 'Rp', 'decimals' => 0, 'thousands' => '.', 'decimal' => ',', 'taxable' => true, 'name' => 'Rupiah', 'name_plural' => 'Rupiah'],
            'USD' => ['symbol' => '$', 'decimals' => 2, 'thousands' => ',', 'decimal' => '.', 'taxable' => false, 'name' => 'Dollar', 'name_plural' => 'Dollars'],
        ];
    }

    /**
     * @return list<string>
     */
    public function codes(): array
    {
        return array_keys($this->currencies);
    }

    /**
     * @return array<int, array{value:string,label:string}>
     */
    public function options(): array
    {
        return array_map(
            fn (string $code) => ['value' => $code, 'label' => $code],
            $this->codes()
        );
    }

    /**
     * @return array{symbol:string,decimals:int,thousands:string,decimal:string,taxable:bool,name?:string,name_plural?:string}
     */
    public function definition(string $code): array
    {
        return $this->currencies[$code] ?? self::fallback()['IDR'];
    }

    public function isTaxable(string $code): bool
    {
        return (bool) $this->definition($code)['taxable'];
    }

    /**
     * Singular currency name, used for the amount-in-words text.
     */
    public function currencyName(string $code): string
    {
        return (string) ($this->definition($code)['name'] ?? $code);
    }

    /**
     * Plural currency name (e.g. "Dollars"), used for English amount-in-words
     * text when the amount isn't exactly 1. Falls back to the singular name
     * for currencies that don't inflect (e.g. Rupiah).
     */
    public function currencyNamePlural(string $code): string
    {
        $def = $this->definition($code);

        return (string) ($def['name_plural'] ?? $def['name'] ?? $code);
    }

    public function symbol(string $code): string
    {
        return (string) $this->definition($code)['symbol'];
    }

    public function decimals(string $code): int
    {
        return (int) $this->definition($code)['decimals'];
    }

    public function format(float $amount, string $code): string
    {
        $def = $this->definition($code);

        return $def['symbol'].' '.number_format(
            $amount,
            (int) $def['decimals'],
            (string) $def['decimal'],
            (string) $def['thousands'],
        );
    }

    /**
     * Format without the currency symbol (for inputs/cells that show the code separately).
     */
    public function number(float $amount, string $code): string
    {
        $def = $this->definition($code);

        return number_format(
            $amount,
            (int) $def['decimals'],
            (string) $def['decimal'],
            (string) $def['thousands'],
        );
    }

    /**
     * Parse a user-typed amount string into a raw float using the currency's separators.
     */
    public function parse(string $input, string $code): float
    {
        $def = $this->definition($code);
        $thousands = (string) $def['thousands'];
        $decimal = (string) $def['decimal'];

        $clean = str_replace($thousands, '', $input);
        if ($decimal !== '.') {
            $clean = str_replace($decimal, '.', $clean);
        }
        $clean = preg_replace('/[^0-9.\-]/', '', $clean) ?? '0';

        return (float) ($clean === '' ? '0' : $clean);
    }
}
