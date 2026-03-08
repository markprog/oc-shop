<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;

interface PaymentGateway
{
    /**
     * Return the gateway identifier (e.g. 'cod', 'bank_transfer').
     */
    public function getCode(): string;

    /**
     * Return the human-readable gateway title.
     */
    public function getTitle(): string;

    /**
     * Process payment for an order.
     * May redirect to a payment provider or return a response.
     */
    public function process(Order $order): RedirectResponse;

    /**
     * Confirm payment (webhook / return callback).
     */
    public function confirm(Order $order, array $data): bool;

    /**
     * Whether this gateway is enabled in settings.
     */
    public function isEnabled(): bool;
}
