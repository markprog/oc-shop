<?php

namespace App\Services\Totals;

use App\Contracts\OrderTotalCalculator;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class CouponCalculator implements OrderTotalCalculator
{
    public function calculate(Order $order): array
    {
        $code   = Session::get('coupon_code');
        $coupon = $code ? Coupon::where('code', $code)->where('status', true)->first() : null;

        $discount = 0.0;
        $title    = 'Coupon';

        if ($coupon) {
            $subTotal = $order->products->sum(fn($p) => $p->price * $p->quantity);

            if ($coupon->type === 'P') {
                $discount = round($subTotal * ($coupon->discount / 100), 2);
            } else {
                $discount = min($coupon->discount, $subTotal);
            }

            $title = "Coupon ({$coupon->code})";
        }

        return [
            'code'       => 'coupon',
            'title'      => $title,
            'value'      => -abs($discount),
            'sort_order' => 7,
        ];
    }
}
