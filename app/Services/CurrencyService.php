<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    private ?Currency $current = null;
    private ?Currency $base    = null;

    public function boot(): void
    {
        $code = Session::get('currency', config('shop.currency'));

        $this->current = Currency::where('code', $code)->where('status', true)->first()
            ?? Currency::where('status', true)->first();

        $this->base = Currency::where('code', config('shop.currency'))->first();
    }

    public function getCurrent(): ?Currency
    {
        return $this->current;
    }

    public function set(string $code): void
    {
        Session::put('currency', $code);
        $this->boot();
    }

    /**
     * Convert a price from the base currency to the current display currency.
     */
    public function convert(float $amount, ?string $from = null, ?string $to = null): float
    {
        $fromRate = $from
            ? (Currency::where('code', $from)->value('value') ?? 1)
            : ($this->base?->value ?? 1);

        $toRate = $to
            ? (Currency::where('code', $to)->value('value') ?? 1)
            : ($this->current?->value ?? 1);

        if ($fromRate == 0) return 0.0;

        return round(($amount / $fromRate) * $toRate, 4);
    }

    /**
     * Format a price with the current currency symbol.
     */
    public function format(float $amount, ?string $currencyCode = null): string
    {
        $currency = $currencyCode
            ? Currency::where('code', $currencyCode)->first()
            : $this->current;

        if (!$currency) {
            return number_format($amount, 2);
        }

        $formatted = number_format(
            $amount,
            (int) $currency->decimal_place,
            $currency->decimal_point ?: '.',
            $currency->thousand_point ?: ','
        );

        return $currency->symbol_left . $formatted . $currency->symbol_right;
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Currency::where('status', true)->orderBy('title')->get();
    }
}
