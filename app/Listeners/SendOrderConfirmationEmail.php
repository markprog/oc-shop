<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail
{
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        if (empty($order->email)) {
            return;
        }

        $adminEmail = Setting::get('config_email');

        // Send to customer
        Mail::raw(
            "Thank you for your order #{$order->order_id}. We will process it shortly.",
            function ($msg) use ($order, $adminEmail) {
                $msg->to($order->email, "{$order->firstname} {$order->lastname}")
                    ->from($adminEmail, Setting::get('config_name', 'Shop'))
                    ->subject("Order Confirmation #{$order->order_id}");
            }
        );

        // Send to admin
        Mail::raw(
            "New order #{$order->order_id} placed by {$order->firstname} {$order->lastname} ({$order->email}). Total: {$order->total}",
            function ($msg) use ($order, $adminEmail) {
                $msg->to($adminEmail)
                    ->subject("New Order #{$order->order_id}");
            }
        );
    }
}
