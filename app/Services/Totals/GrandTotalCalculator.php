<?php

namespace App\Services\Totals;

use App\Contracts\OrderTotalCalculator;
use App\Models\Order;

class GrandTotalCalculator implements OrderTotalCalculator
{
    public function calculate(Order $order): array
    {
        // Grand total is calculated externally from all totals.
        // This calculator just returns 0 — the actual sum is done by OrderTotalsService.
        // Alternatively it can load all existing totals and sum them.
        $sum = $order->totals()->sum('value');

        return [
            'code'       => 'total',
            'title'      => 'Total',
            'value'      => round($sum, 2),
            'sort_order' => 9999,
        ];
    }
}
