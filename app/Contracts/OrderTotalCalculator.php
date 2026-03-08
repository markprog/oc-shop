<?php

namespace App\Contracts;

use App\Models\Order;

interface OrderTotalCalculator
{
    /**
     * Calculate the total and return a line item array.
     *
     * @return array{code: string, title: string, value: float, sort_order: int}
     */
    public function calculate(Order $order): array;
}
