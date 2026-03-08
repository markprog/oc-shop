<?php

namespace App\Http\Controllers\Storefront\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Product::with('description', 'images')
            ->active()
            ->orderByDesc('viewed')
            ->limit(8)
            ->get();

        $banners = Banner::with('images')->where('status', true)->get();

        return view('storefront.catalog.home', compact('featured', 'banners'));
    }
}
