<?php

namespace App\Http\Controllers\Storefront\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        $items = $this->cart->getItems();
        $total = $this->cart->getTotal();
        return view('storefront.checkout.cart', compact('items', 'total'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->add(
            $request->integer('product_id'),
            $request->integer('quantity'),
            $request->input('option', []),
            $request->integer('subscription_plan_id') ?: null,
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'count' => $this->cart->count()]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, int $cartId): RedirectResponse|JsonResponse
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);
        $this->cart->update($cartId, $request->integer('quantity'));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'total' => $this->cart->getTotal()]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(int $cartId): RedirectResponse|JsonResponse
    {
        $this->cart->remove($cartId);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }
}
