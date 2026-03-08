<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private CartService        $cart,
        private OrderTotalsService $totals,
        private TaxService         $tax,
    ) {}

    /**
     * Place a new order from the current cart session.
     */
    public function createFromCart(array $checkoutData): Order
    {
        return DB::transaction(function () use ($checkoutData) {
            $customer = Auth::guard('web')->user();
            $items    = $this->cart->getItems();

            if ($items->isEmpty()) {
                throw new \RuntimeException('Cannot place order with empty cart.');
            }

            // Snapshot shipping address
            $shippingAddress = $checkoutData['shipping_address'];

            $order = Order::create([
                'invoice_prefix'      => 'INV-',
                'store_id'            => 0,
                'store_name'          => config('shop.name'),
                'store_url'           => config('app.url'),
                'customer_id'         => $customer?->customer_id,
                'customer_group_id'   => $customer?->customer_group_id ?? 1,
                'firstname'           => $shippingAddress['firstname'],
                'lastname'            => $shippingAddress['lastname'],
                'email'               => $customer?->email ?? $checkoutData['email'],
                'telephone'           => $shippingAddress['telephone'] ?? '',
                'payment_firstname'   => $shippingAddress['firstname'],
                'payment_lastname'    => $shippingAddress['lastname'],
                'payment_address_1'   => $shippingAddress['address_1'],
                'payment_address_2'   => $shippingAddress['address_2'] ?? '',
                'payment_city'        => $shippingAddress['city'],
                'payment_postcode'    => $shippingAddress['postcode'],
                'payment_country'     => $shippingAddress['country'],
                'payment_zone'        => $shippingAddress['zone'] ?? '',
                'payment_method'      => $checkoutData['payment_method'],
                'shipping_firstname'  => $shippingAddress['firstname'],
                'shipping_lastname'   => $shippingAddress['lastname'],
                'shipping_address_1'  => $shippingAddress['address_1'],
                'shipping_address_2'  => $shippingAddress['address_2'] ?? '',
                'shipping_city'       => $shippingAddress['city'],
                'shipping_postcode'   => $shippingAddress['postcode'],
                'shipping_country'    => $shippingAddress['country'],
                'shipping_zone'       => $shippingAddress['zone'] ?? '',
                'shipping_method'     => $checkoutData['shipping_method'],
                'comment'             => $checkoutData['comment'] ?? '',
                'total'               => 0,
                'order_status_id'     => (int) config('shop.order_status_id', 1),
                'language_id'         => 1,
                'currency_id'         => 1,
                'currency_code'       => 'USD',
                'currency_value'      => 1,
                'ip'                  => request()->ip(),
            ]);

            // Add products
            foreach ($items as $item) {
                $product = $item->product;

                $order->products()->create([
                    'product_id'   => $product->product_id,
                    'name'         => $product->description?->name ?? $product->model,
                    'model'        => $product->model,
                    'quantity'     => $item->quantity,
                    'price'        => $product->price,
                    'total'        => $product->price * $item->quantity,
                    'tax'          => $this->tax->calculate($product->price, $product->tax_class_id ?? 0),
                    'reward'       => $product->points ?? 0,
                ]);

                // Decrement stock if tracked
                if ($product->quantity > 0) {
                    $product->decrement('quantity', $item->quantity);
                }
            }

            // Calculate and store totals
            $orderTotal = $this->totals->calculate($order);

            $order->update(['total' => $orderTotal]);

            // Add initial history
            $order->histories()->create([
                'order_status_id' => $order->order_status_id,
                'notify'          => false,
                'comment'         => 'Order placed.',
            ]);

            // Clear cart
            $this->cart->clear();

            event(new OrderPlaced($order));

            return $order;
        });
    }
}
