<?php

namespace App\Services\Totals;

use App\Contracts\OrderTotalCalculator;
use App\Models\Order;
use App\Services\TaxService;

class TaxTotalCalculator implements OrderTotalCalculator
{
    public function __construct(private TaxService $tax)
    {
    }

    public function calculate(Order $order): array
    {
        $taxTotal = $order->products->sum(fn($p) => $p->tax * $p->quantity);

        return [
            'code'       => 'tax',
            'title'      => 'VAT',
            'value'      => round($taxTotal, 2),
            'sort_order' => 5,
        ];
    }
}
