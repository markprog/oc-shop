<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Review;

class ReviewPolicy
{
    public function delete(Customer $customer, Review $review): bool
    {
        return $review->customer_id === $customer->customer_id;
    }
}
