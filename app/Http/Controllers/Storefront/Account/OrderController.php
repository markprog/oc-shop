<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $orders = auth('web')->user()->orders()->with('status')->latest()->paginate(10);
        return view('storefront.account.orders.index', compact('orders'));
    }

    public function show(int $id): View
    {
        $order = auth('web')->user()->orders()
            ->with('products.options', 'totals', 'histories.status', 'status')
            ->findOrFail($id);

        $this->authorize('view', $order);

        return view('storefront.account.orders.show', compact('order'));
    }
}
