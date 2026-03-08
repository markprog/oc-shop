<?php

namespace App\Services\Shipping;

use App\Contracts\ShippingMethod;
use App\Models\Setting;

class FlatShipping implements ShippingMethod
{
    public function getCode(): string
    {
        return 'flat';
    }

    public function getTitle(): string
    {
        return Setting::get('shipping_flat_title', 'Flat Shipping Rate');
    }

    public function getQuote(array $address, array $cartItems): ?array
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $cost      = (float) Setting::get('shipping_flat_cost', 5.00);
        $taxClassId = (int) Setting::get('shipping_flat_tax_class_id', 0);

        return [
            'id'    => $this->getCode() . '.' . $this->getCode(),
            'title' => $this->getTitle(),
            'cost'  => $cost,
            'tax_class_id' => $taxClassId,
        ];
    }

    public function isEnabled(): bool
    {
        return (bool) Setting::get('shipping_flat_status', true);
    }
}
