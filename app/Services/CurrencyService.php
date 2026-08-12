<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * CurrencyService — handles dynamic currency conversion
 * using the free Frankfurter API (https://api.frankfurter.app).
 *
 * Falls back to static config rates if the API is unreachable.
 */
class CurrencyService
{
    /**
     * Frankfurter base URL (no key required — free & open).
     */
    private const FRANKFURTER_URL = 'https://api.frankfurter.app/latest';

    /**
     * Convert an amount from one currency to another.
     *
     * Steps:
     * 1. If $from === $to, return the original amount unchanged.
     * 2. Try to fetch a live rate from Frankfurter (cached for 1 hour).
     * 3. On failure, fall back to static rates from config/currencies.php.
     *
     * @param  float  $amount  The original price.
     * @param  string $from    Source currency code (e.g. 'USD').
     * @param  string $to      Target currency code (e.g. 'TND', 'EUR').
     * @return float           Converted amount, rounded to 2 decimals.
     */
    public function convert(float $amount, string $from, string $to): float
    {
        // No conversion needed if both currencies are the same.
        if ($from === $to) {
            return $amount;
        }

        $rate = $this->getRate($from, $to);

        return round($amount * $rate, 2);
    }

    /**
     * Return both the original and converted price in a structured array.
     *
     * @param  float  $amount      Original price.
     * @param  string $from        Source currency.
     * @param  string $to          Target currency.
     * @param  string $symbol      Currency symbol for display (e.g. '$', '€').
     * @return array{original: float, converted: float, rate: float, currency: string, formatted: string}
     */
    public function priceWithOriginal(float $amount, string $from, string $to, string $symbol = '$'): array
    {
        $rate = $this->getRate($from, $to);
        $converted = round($amount * $rate, 2);

        return [
            'original'     => $amount,
            'originalFormatted' => $symbol . number_format($amount, 2),
            'converted'    => $converted,
            'rate'         => $rate,
            'currency'     => $to,
            'formatted'    => config("currencies.symbols.$to", $symbol) . number_format($converted, 2),
        ];
    }

    // ---------------------------------------------------------------
    //  Internal helpers
    // ---------------------------------------------------------------

    /**
     * Get the exchange rate from $from to $to.
     *
     * Priority:
     * 1. Live Frankfurter rate (cached 60 min).
     * 2. Static config fallback.
     *
     * @param  string $from  Source currency code.
     * @param  string $to    Target currency code.
     * @return float         The multiplier (1 $from = X $to).
     */
    private function getRate(string $from, string $to): float
    {
        $cacheKey = "fx_rate_{$from}_{$to}";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($from, $to) {
            // 1. Try live Frankfurter API
            try {
                $response = Http::timeout(5)
                    ->acceptJson()
                    ->get(self::FRANKFURTER_URL, [
                        'from' => $from,
                        'to'   => $to,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $rate = $data['rates'][$to] ?? null;

                    if ($rate !== null && is_numeric($rate)) {
                        return (float) $rate;
                    }
                }
            } catch (\Exception) {
                // Network issue — fall through to config fallback.
            }

            // 2. Fallback to static config rates.
            $rates = config('currencies.rates', []);

            // Convert via EUR as bridge if direct pair is missing.
            if ($from === 'EUR') {
                return (float) ($rates[$to] ?? 1.0);
            }

            $fromRate = $rates[$from] ?? null;
            $toRate   = $rates[$to]   ?? null;

            if ($fromRate && $toRate) {
                return round($toRate / $fromRate, 6);
            }

            return 1.0;
        });
    }
}
