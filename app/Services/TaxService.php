<?php

namespace App\Services;

use App\Models\TaxClass;
use App\Models\TaxRate;

class TaxService
{
    /**
     * Calculate tax amount for a price given a tax class ID.
     */
    public function calculate(float $price, int $taxClassId, string $based = 'shipping'): float
    {
        $taxClass = TaxClass::with('rules.rate.geoZone')->find($taxClassId);

        if (!$taxClass) {
            return 0.0;
        }

        $tax = 0.0;

        foreach ($taxClass->rules()->orderBy('priority')->get() as $rule) {
            $rate = $rule->rate;
            if (!$rate) continue;

            if ($rate->type === 'P') {
                $tax += round($price * ($rate->rate / 100), 4);
            } elseif ($rate->type === 'F') {
                $tax += $rate->rate;
            }
        }

        return round($tax, 2);
    }

    /**
     * Get all applicable tax rates for a tax class.
     */
    public function getRates(int $taxClassId): array
    {
        $taxClass = TaxClass::with('rules.rate')->find($taxClassId);
        if (!$taxClass) return [];

        return $taxClass->rules->map(fn($rule) => [
            'name' => $rule->rate->name ?? '',
            'rate' => $rule->rate->rate ?? 0,
            'type' => $rule->rate->type ?? 'P',
        ])->toArray();
    }

    /**
     * Format tax rate description for display.
     */
    public function formatRate(float $rate, string $type = 'P'): string
    {
        if ($type === 'P') {
            return number_format($rate, 2) . '%';
        }

        return number_format($rate, 2);
    }
}
