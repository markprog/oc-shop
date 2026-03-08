<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class CodGateway implements PaymentGateway
{
    public function getCode(): string
    {
        return 'cod';
    }

    public function getTitle(): string
    {
        return Setting::get('payment_cod_title', 'Cash on Delivery');
    }

    public function process(Order $order): RedirectResponse
    {
        // COD requires no remote processing — go directly to success
        return redirect()->route('checkout.success', ['orderId' => $order->order_id]);
    }

    public function confirm(Order $order, array $data): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('payment_cod_status', true);
    }
}
