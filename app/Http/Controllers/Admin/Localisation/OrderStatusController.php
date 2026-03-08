<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\OrderStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderStatusController extends Controller
{
    public function index(): View
    {
        $statuses = OrderStatus::orderBy('name')->paginate(20);
        return view('admin.localisation.order-status.index', compact('statuses'));
    }

    public function create(): View
    {
        return view('admin.localisation.order-status.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        OrderStatus::create(['name' => $request->name]);
        return redirect()->route('admin.localisation.order-status.index')->with('success', 'Order status added.');
    }

    public function edit(OrderStatus $orderStatus): View
    {
        return view('admin.localisation.order-status.form', compact('orderStatus'));
    }

    public function update(Request $request, OrderStatus $orderStatus): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        $orderStatus->update(['name' => $request->name]);
        return redirect()->route('admin.localisation.order-status.index')->with('success', 'Order status updated.');
    }

    public function destroy(OrderStatus $orderStatus): RedirectResponse
    {
        $orderStatus->delete();
        return redirect()->route('admin.localisation.order-status.index')->with('success', 'Order status deleted.');
    }
}
