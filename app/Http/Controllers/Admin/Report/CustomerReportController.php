<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerReportController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->date_from ?? now()->subMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        $topCustomers = Order::selectRaw('customer_id, firstname, lastname, email, COUNT(*) as order_count, SUM(total) as total_spent')
            ->whereBetween('date_added', [$dateFrom, $dateTo])
            ->whereNotNull('customer_id')
            ->groupBy('customer_id', 'firstname', 'lastname', 'email')
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();

        $newCustomers = Customer::selectRaw('DATE(date_added) as date, COUNT(*) as count')
            ->whereBetween('date_added', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.report.customer', compact('topCustomers', 'newCustomers', 'dateFrom', 'dateTo'));
    }
}
