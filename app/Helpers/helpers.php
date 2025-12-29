<?php

if (! function_exists('presentPrice')) {
    /**
     * Present a price amount as a formatted string with currency.
     * @param float|int|null $amount
     * @param string|null $currency
     * @return string
     */
    function presentPrice($amount, $currency = null)
    {
        $amount = $amount ?? 0;
        // default currency from config or USD
        $currency = $currency ?? config('app.currency') ?? 'USD';

        // basic currency symbol map
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'VND' => '₫',
        ];

        $symbol = $symbols[strtoupper($currency)] ?? null;

        // If the currency is VND, format without decimals
        if (strtoupper($currency) === 'VND') {
            $formatted = number_format((float) $amount, 0, '.', ',');
            return $symbol ? ($formatted . ' ' . $symbol) : ($formatted . ' ' . $currency);
        }

        $formatted = number_format((float) $amount, 2, '.', ',');

        if ($symbol) {
            // put symbol before amount for common currencies
            if (in_array(strtoupper($currency), ['USD','EUR','GBP'])) {
                return $symbol . $formatted;
            }
            return $formatted . ' ' . $symbol;
        }

        return $formatted . ' ' . strtoupper($currency);
    }
}

if (! function_exists('formatUSD')) {
    /**
     * Format an amount as USD with a leading dollar sign.
     * Kept as a thin wrapper around presentPrice for convenience.
     * @param float|int|null $amount
     * @return string
     */
    function formatUSD($amount)
    {
        return presentPrice($amount, 'USD');
    }
}
