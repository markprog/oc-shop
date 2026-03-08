<?php

namespace App\Http\Controllers\Storefront\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query   = $request->input('search', '');
        $products = collect();

        if (strlen($query) >= 3) {
            $products = Product::with('description', 'images')
                ->active()
                ->whereHas('descriptions', fn($q) => $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('tag', 'like', "%{$query}%"))
                ->paginate(15)
                ->withQueryString();
        }

        return view('storefront.catalog.search', compact('query', 'products'));
    }
}
