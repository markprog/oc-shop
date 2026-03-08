<?php

namespace App\Providers;

use App\Events\CustomerRegistered;
use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Events\ProductReviewed;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderStatusEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            SendOrderConfirmationEmail::class,
        ],
        OrderStatusChanged::class => [
            SendOrderStatusEmail::class,
        ],
        CustomerRegistered::class => [
            // SendWelcomeEmail::class,
        ],
        ProductReviewed::class => [
            // NotifyAdminOfNewReview::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
