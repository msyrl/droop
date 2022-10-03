<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function format($amount, string $toFormat = 'idr'): string
    {
        $currencies = [
            'idr' => function () use ($amount) {
                return number_format(
                    $amount,
                    0,
                    ',',
                    '.'
                );
            },
            'default' => function () use ($amount) {
                return number_format($amount);
            },
        ];

        if (!array_key_exists($toFormat, $currencies)) {
            $toFormat = 'default';
        }

        return $currencies[$toFormat]();
    }
}
