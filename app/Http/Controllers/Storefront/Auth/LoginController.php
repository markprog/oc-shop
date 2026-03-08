<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\LoginRequest;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('storefront.account.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        $customer = Auth::guard('web')->user();

        if (!$customer->status) {
            Auth::guard('web')->logout();
            return back()->withErrors(['email' => 'Your account has been disabled.']);
        }

        app(CartService::class)->mergeGuestCart($customer);

        return redirect()->intended(route('account.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
