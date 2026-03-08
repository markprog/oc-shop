<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class BankTransferGateway implements PaymentGateway
{
    public function getCode(): string
    {
        return 'bank_transfer';
    }

    public function getTitle(): string
    {
        return Setting::get('payment_bank_transfer_title', 'Bank Transfer');
    }

    public function process(Order $order): RedirectResponse
    {
        // Display bank details and redirect to success
        return redirect()->route('checkout.success', ['orderId' => $order->order_id])
            ->with('payment_instructions', Setting::get('payment_bank_transfer_instructions', ''));
    }

    public function confirm(Order $order, array $data): bool
    {
        // Manual confirmation required by admin
        return false;
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('payment_bank_transfer_status', false);
    }
}
