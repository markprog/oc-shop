<?php

namespace App\Http\Controllers\Storefront\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Services\PaymentService;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService    $cart,
        private OrderService   $orderService,
        private ShippingService $shippingService,
        private PaymentService  $paymentService,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty.');
        }

        return view('storefront.checkout.checkout', [
            'cartItems'       => $this->cart->getItems(),
            'cartTotal'       => $this->cart->getTotal(),
            'shippingMethods' => [],
            'paymentMethods'  => [],
        ]);
    }

    public function shippingAddress(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname'  => ['required', 'string', 'max:32'],
            'lastname'   => ['required', 'string', 'max:32'],
            'address_1'  => ['required', 'string', 'max:128'],
            'city'       => ['required', 'string', 'max:128'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'zone_id'    => ['required', 'integer'],
            'postcode'   => ['nullable', 'string', 'max:10'],
        ]);

        session(['checkout.shipping_address' => $request->only(
            'firstname', 'lastname', 'company', 'address_1', 'address_2',
            'city', 'postcode', 'country_id', 'zone_id'
        )]);

        return redirect()->route('checkout.shipping_method');
    }

    public function shippingMethod(Request $request): View
    {
        $address  = (object) session('checkout.shipping_address', []);
        $weight   = $this->cart->getWeight();
        $methods  = $this->shippingService->getAvailableMethods($address, $weight);

        return view('storefront.checkout.shipping_method', compact('methods'));
    }

    public function setShippingMethod(Request $request): RedirectResponse
    {
        $request->validate(['shipping_method' => ['required', 'string']]);
        session(['checkout.shipping_method' => $request->input('shipping_method')]);
        return redirect()->route('checkout.payment_method');
    }

    public function paymentMethod(Request $request): View
    {
        $methods = $this->paymentService->getAvailableMethods();
        return view('storefront.checkout.payment_method', compact('methods'));
    }

    public function setPaymentMethod(Request $request): RedirectResponse
    {
        $request->validate(['payment_method' => ['required', 'string']]);
        session(['checkout.payment_method' => $request->input('payment_method')]);
        return redirect()->route('checkout.confirm');
    }

    public function confirm(): View
    {
        return view('storefront.checkout.confirm', [
            'items'          => $this->cart->getItems(),
            'total'          => $this->cart->getTotal(),
            'shippingMethod' => session('checkout.shipping_method'),
            'paymentMethod'  => session('checkout.payment_method'),
        ]);
    }

    public function place(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->orderService->createFromCheckout(
                session('checkout'),
                auth()->user(),
                $request->validated()
            );

            session()->forget('checkout');

            return redirect()->route('checkout.success', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            return redirect()->route('checkout.confirm')->with('error', $e->getMessage());
        }
    }

    public function success(Request $request): View
    {
        $orderId = $request->query('order_id');
        return view('storefront.checkout.success', compact('orderId'));
    }

    public function failure(): View
    {
        return view('storefront.checkout.failure');
    }
}
