<?php

namespace App\Services\Shipping;

use App\Contracts\ShippingMethod;
use App\Models\Setting;

class FreeShipping implements ShippingMethod
{
    public function getCode(): string
    {
        return 'free';
    }

    public function getTitle(): string
    {
        return Setting::get('shipping_free_title', 'Free Shipping');
    }

    public function getQuote(array $address, array $cartItems): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $minimum = (float) Setting::get('shipping_free_minimum', 0);

        $subtotal = array_sum(array_map(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1), $cartItems));

        if ($minimum > 0 && $subtotal < $minimum) {
            return null;
        }

        return [
            'id'           => $this->getCode() . '.' . $this->getCode(),
            'title'        => $this->getTitle(),
            'cost'         => 0.00,
            'tax_class_id' => 0,
        ];
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('shipping_free_status', false);
    }
}
