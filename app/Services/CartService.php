<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    private string $sessionKey = 'cart_session_id';

    public function getSessionId(): string
    {
        if (!Session::has($this->sessionKey)) {
            Session::put($this->sessionKey, uniqid('cart_', true));
        }

        return Session::get($this->sessionKey);
    }

    /**
     * Add product to cart.
     */
    public function add(int $productId, int $quantity = 1, array $options = []): void
    {
        $optionKey = md5(serialize($options));

        $existing = Cart::where('session_id', $this->getSessionId())
            ->where('product_id', $productId)
            ->where('option_key', $optionKey)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);
        } else {
            Cart::create([
                'customer_id' => Auth::guard('web')->id(),
                'session_id'  => $this->getSessionId(),
                'product_id'  => $productId,
                'quantity'    => $quantity,
                'option'      => $options,
                'option_key'  => $optionKey,
            ]);
        }
    }

    /**
     * Update cart item quantity.
     */
    public function update(int $cartId, int $quantity): void
    {
        Cart::where('cart_id', $cartId)
            ->where('session_id', $this->getSessionId())
            ->update(['quantity' => $quantity]);
    }

    /**
     * Remove item from cart.
     */
    public function remove(int $cartId): void
    {
        Cart::where('cart_id', $cartId)
            ->where('session_id', $this->getSessionId())
            ->delete();
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): void
    {
        Cart::where('session_id', $this->getSessionId())->delete();
    }

    /**
     * Get all cart items with product data.
     */
    public function getItems(): \Illuminate\Database\Eloquent\Collection
    {
        return Cart::where('session_id', $this->getSessionId())
            ->with(['product.description', 'product.images'])
            ->get();
    }

    /**
     * Get total number of items in cart.
     */
    public function getTotalQuantity(): int
    {
        return (int) Cart::where('session_id', $this->getSessionId())->sum('quantity');
    }

    /**
     * Get cart subtotal.
     */
    public function getTotal(): float
    {
        $items = $this->getItems();
        return (float) $items->sum(fn($item) => $item->product->price * $item->quantity);
    }

    /**
     * Merge guest cart into authenticated customer cart after login.
     */
    public function mergeGuestCart(int $customerId): void
    {
        Cart::where('session_id', $this->getSessionId())
            ->whereNull('customer_id')
            ->update(['customer_id' => $customerId]);
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return Cart::where('session_id', $this->getSessionId())->count() === 0;
    }
}
