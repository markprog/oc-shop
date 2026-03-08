<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $customer = auth('web')->user()->load('defaultAddress');
        return view('storefront.account.account', compact('customer'));
    }
}
