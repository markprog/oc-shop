<?php

namespace App\Contracts;

use App\Models\Order;

interface ShippingMethod
{
    /**
     * Return method code (e.g. 'flat', 'free', 'weight').
     */
    public function getCode(): string;

    /**
     * Return human-readable method name.
     */
    public function getTitle(): string;

    /**
     * Calculate shipping cost for a given cart/order context.
     */
    public function getQuote(array $address, array $cartItems): ?array;

    /**
     * Whether this shipping method is enabled.
     */
    public function isEnabled(): bool;
}
