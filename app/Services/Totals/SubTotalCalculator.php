<?php

namespace App\Services\Totals;

use App\Contracts\OrderTotalCalculator;
use App\Models\Order;

class SubTotalCalculator implements OrderTotalCalculator
{
    public function calculate(Order $order): array
    {
        $subTotal = $order->products->sum(fn($p) => $p->price * $p->quantity);

        return [
            'code'       => 'sub_total',
            'title'      => 'Sub-Total',
            'value'      => round($subTotal, 2),
            'sort_order' => 1,
        ];
    }
}
