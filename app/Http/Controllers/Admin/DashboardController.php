<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders'    => Order::count(),
            'total_customers' => Customer::count(),
            'total_products'  => Product::count(),
            'pending_reviews' => Review::where('status', false)->count(),
        ];

        $recentOrders = Order::with('status', 'customer')
            ->latest()
            ->limit(10)
            ->get();

        $salesData = Order::selectRaw('DATE(date_added) as date, SUM(total) as revenue')
            ->where('date_added', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'salesData'));
    }
}
