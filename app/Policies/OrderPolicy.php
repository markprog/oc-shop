<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Order;

class OrderPolicy
{
    public function view(Customer $customer, Order $order): bool
    {
        return $order->customer_id === $customer->customer_id;
    }
}
