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
use App\Services\CartService;
use App\Services\CurrencyService;
use App\Services\OrderTotalsService;
use App\Services\SeoUrlService;
use App\Services\TaxService;
use App\Services\Totals\CouponCalculator;
use App\Services\Totals\GrandTotalCalculator;
use App\Services\Totals\ShippingTotalCalculator;
use App\Services\Totals\SubTotalCalculator;
use App\Services\Totals\TaxTotalCalculator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // CartService as singleton — same instance throughout request
        $this->app->singleton(CartService::class);

        // CurrencyService as singleton
        $this->app->singleton(CurrencyService::class);

        // SeoUrlService as singleton
        $this->app->singleton(SeoUrlService::class);

        // TaxService
        $this->app->singleton(TaxService::class);

        // OrderTotalsService with configured pipeline
        $this->app->singleton(OrderTotalsService::class, function ($app) {
            return new OrderTotalsService([
                $app->make(SubTotalCalculator::class),
                $app->make(ShippingTotalCalculator::class),
                $app->make(TaxTotalCalculator::class),
                $app->make(CouponCalculator::class),
                $app->make(GrandTotalCalculator::class),
            ]);
        });
    }

    public function boot(): void
    {
        // Policies
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Address::class, AddressPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(ProductReturn::class, ReturnPolicy::class);
    }
}
