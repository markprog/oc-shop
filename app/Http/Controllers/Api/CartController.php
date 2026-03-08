<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $items = $this->cart->getItems();
        return response()->json([
            'items' => $items,
            'total' => $this->cart->getTotal(),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,product_id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'options'    => ['nullable', 'array'],
        ]);

        $this->cart->add($request->product_id, $request->quantity, $request->input('options', []));

        return response()->json(['message' => 'Product added to cart.', 'count' => $this->cart->getTotalQuantity()]);
    }

    public function update(Request $request, int $cartId): JsonResponse
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);

        if ($request->quantity === 0) {
            $this->cart->remove($cartId);
        } else {
            $this->cart->update($cartId, $request->quantity);
        }

        return response()->json(['message' => 'Cart updated.']);
    }

    public function remove(int $cartId): JsonResponse
    {
        $this->cart->remove($cartId);
        return response()->json(['message' => 'Item removed from cart.']);
    }

    public function clear(): JsonResponse
    {
        $this->cart->clear();
        return response()->json(['message' => 'Cart cleared.']);
    }
}
