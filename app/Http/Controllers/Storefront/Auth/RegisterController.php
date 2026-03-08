<?php

namespace App\Http\Controllers\Storefront\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\RegisterRequest;
use App\Models\Customer;
use App\Models\Setting;
use App\Events\CustomerRegistered;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showForm(): View
    {
        return view('storefront.account.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $customer = Customer::create([
            'firstname'         => $request->firstname,
            'lastname'          => $request->lastname,
            'email'             => $request->email,
            'telephone'         => $request->telephone,
            'password'          => Hash::make($request->password),
            'newsletter'        => $request->boolean('newsletter'),
            'customer_group_id' => Setting::get('config_customer_group_id', 1),
            'store_id'          => Setting::get('config_store_id', 0),
            'ip'                => $request->ip(),
            'status'            => !(bool) Setting::get('config_customer_approval'),
        ]);

        event(new CustomerRegistered($customer));

        if (Setting::get('config_customer_approval')) {
            return redirect()->route('home')
                ->with('success', 'Your account is awaiting approval.');
        }

        Auth::guard('web')->login($customer);
        app(CartService::class)->mergeGuestCart($customer);

        return redirect()->route('account.index');
    }
}
