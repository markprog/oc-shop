<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BootstrapCustomer
{
    public function __construct(private CartService $cart)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        // Merge guest cart items with customer cart after login
        if (auth('web')->check()) {
            $customer = auth('web')->user();

            if (!$customer->status) {
                auth('web')->logout();
                return redirect()->route('login')->with('error', 'Your account has been suspended.');
            }

            // Merge any guest cart items
            $this->cart->mergeGuestCart($customer->customer_id);
        }

        return $next($request);
    }
}
