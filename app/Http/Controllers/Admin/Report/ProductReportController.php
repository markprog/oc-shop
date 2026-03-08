<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReportController extends Controller
{
    public function index(Request $request): View
    {
        $dateFrom = $request->date_from ?? now()->subMonth()->toDateString();
        $dateTo   = $request->date_to   ?? now()->toDateString();

        $viewed = Product::with('description')
            ->orderByDesc('viewed')
            ->limit(20)
            ->get();

        $purchased = OrderProduct::selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(total) as total_revenue')
            ->whereHas('order', fn($q) => $q->whereBetween('date_added', [$dateFrom, $dateTo]))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        return view('admin.report.product', compact('viewed', 'purchased', 'dateFrom', 'dateTo'));
    }
}
