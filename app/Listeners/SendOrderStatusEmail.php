<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusEmail
{
    public function handle(OrderStatusChanged $event): void
    {
        if (!$event->notify || empty($event->order->email)) {
            return;
        }

        $order      = $event->order;
        $statusName = $event->newStatus->name;
        $adminEmail = Setting::get('config_email');
        $shopName   = Setting::get('config_name', 'Shop');

        Mail::raw(
            "Your order #{$order->order_id} status has been updated to: {$statusName}.\n\n" . $event->comment,
            function ($msg) use ($order, $adminEmail, $shopName, $statusName) {
                $msg->to($order->email, "{$order->firstname} {$order->lastname}")
                    ->from($adminEmail, $shopName)
                    ->subject("Order #{$order->order_id} — {$statusName}");
            }
        );
    }
}
