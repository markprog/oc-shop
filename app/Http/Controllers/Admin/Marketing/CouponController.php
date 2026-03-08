<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $query = Coupon::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        $coupons = $query->orderByDesc('coupon_id')->paginate(20)->withQueryString();

        return view('admin.marketing.coupon.index', compact('coupons'));
    }

    public function create(): View
    {
        $products   = Product::with('description')->get();
        $categories = Category::with('description')->get();
        return view('admin.marketing.coupon.form', compact('products', 'categories'));
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        $coupon = Coupon::create($request->couponData());

        $coupon->products()->sync($request->input('product_ids', []));
        $coupon->categories()->sync($request->input('category_ids', []));

        return redirect()->route('admin.marketing.coupon.index')->with('success', 'Coupon added.');
    }

    public function edit(Coupon $coupon): View
    {
        $coupon->load('products', 'categories');
        $products   = Product::with('description')->get();
        $categories = Category::with('description')->get();
        return view('admin.marketing.coupon.form', compact('coupon', 'products', 'categories'));
    }

    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($request->couponData());

        $coupon->products()->sync($request->input('product_ids', []));
        $coupon->categories()->sync($request->input('category_ids', []));

        return redirect()->route('admin.marketing.coupon.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();
        return redirect()->route('admin.marketing.coupon.index')->with('success', 'Coupon deleted.');
    }
}
