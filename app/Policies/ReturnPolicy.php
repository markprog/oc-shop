<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\ProductReturn;

class ReturnPolicy
{
    public function view(Customer $customer, ProductReturn $return): bool
    {
        return $return->customer_id === $customer->customer_id;
    }
}
