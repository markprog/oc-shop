<?php

namespace App\Services;

use App\Contracts\OrderTotalCalculator;
use App\Models\Order;

class OrderTotalsService
{
    /** @var OrderTotalCalculator[] */
    private array $calculators;

    public function __construct(array $calculators = [])
    {
        $this->calculators = $calculators;
    }

    /**
     * Run all total calculators in order and persist to order_totals.
     * Returns grand total.
     */
    public function calculate(Order $order): float
    {
        $order->totals()->delete();

        $grandTotal = 0.0;

        foreach ($this->calculators as $calculator) {
            $result = $calculator->calculate($order);

            $order->totals()->create([
                'code'       => $result['code'],
                'title'      => $result['title'],
                'value'      => $result['value'],
                'sort_order' => $result['sort_order'],
            ]);

            $grandTotal += $result['value'];
        }

        return round($grandTotal, 2);
    }

    /**
     * Get preview totals without persisting (for checkout confirmation page).
     */
    public function preview(Order $order): array
    {
        $totals     = [];
        $grandTotal = 0.0;

        foreach ($this->calculators as $calculator) {
            $result      = $calculator->calculate($order);
            $totals[]    = $result;
            $grandTotal += $result['value'];
        }

        $totals[] = [
            'code'       => 'total',
            'title'      => 'Total',
            'value'      => round($grandTotal, 2),
            'sort_order' => 9999,
        ];

        return $totals;
    }
}
