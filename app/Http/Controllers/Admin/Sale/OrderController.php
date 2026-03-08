<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with('status', 'customer');

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        if ($request->filled('customer')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'like', '%' . $request->customer . '%')
                  ->orWhere('lastname', 'like', '%' . $request->customer . '%')
                  ->orWhere('email', 'like', '%' . $request->customer . '%');
            });
        }
        if ($request->filled('status_id')) {
            $query->where('order_status_id', $request->status_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date_added', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_added', '<=', $request->date_to);
        }

        $orders   = $query->orderByDesc('date_added')->paginate(20)->withQueryString();
        $statuses = OrderStatus::all();

        return view('admin.sale.order.index', compact('orders', 'statuses'));
    }

    public function show(Order $order): View
    {
        $order->load('products.options', 'totals', 'histories.status', 'status');
        $statuses = OrderStatus::all();
        return view('admin.sale.order.show', compact('order', 'statuses'));
    }

    public function addHistory(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'order_status_id' => ['required', 'exists:order_statuses,order_status_id'],
            'comment'         => ['nullable', 'string'],
            'notify'          => ['boolean'],
        ]);

        $order->update(['order_status_id' => $request->order_status_id]);

        $order->histories()->create([
            'order_status_id' => $request->order_status_id,
            'notify'          => $request->boolean('notify'),
            'comment'         => $request->comment ?? '',
        ]);

        return back()->with('success', 'Order history added.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('admin.sale.order.index')->with('success', 'Order deleted.');
    }
}
