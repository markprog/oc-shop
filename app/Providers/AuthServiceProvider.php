<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductReturn;
use App\Models\Review;
use App\Policies\AddressPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ReturnPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class         => OrderPolicy::class,
        Address::class       => AddressPolicy::class,
        Review::class        => ReviewPolicy::class,
        ProductReturn::class => ReturnPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
