<?php

namespace App\Services\Totals;

use App\Contracts\OrderTotalCalculator;
use App\Models\Order;
use App\Models\Setting;

class ShippingTotalCalculator implements OrderTotalCalculator
{
    public function calculate(Order $order): array
    {
        // Shipping method stored as "method_code.option_code | Title"
        $shippingMethod = $order->shipping_method;
        $cost           = (float) Setting::get('shipping_flat_cost', 5.00);

        // In a real implementation: parse method code and look up configured cost
        return [
            'code'       => 'shipping',
            'title'      => 'Flat Shipping Rate',
            'value'      => $cost,
            'sort_order' => 3,
        ];
    }
}
