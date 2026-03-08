<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $wishlist = auth('web')->user()->wishlist()->with('product.description', 'product.images')->get();
        return view('storefront.account.wishlist', compact('wishlist'));
    }

    public function add(int $productId): RedirectResponse
    {
        Wishlist::firstOrCreate([
            'customer_id' => auth()->id(),
            'product_id'  => $productId,
        ]);
        return back()->with('success', 'Product added to wishlist.');
    }

    public function remove(int $productId): RedirectResponse
    {
        Wishlist::where('customer_id', auth()->id())->where('product_id', $productId)->delete();
        return back()->with('success', 'Product removed from wishlist.');
    }
}
