<?php

namespace App\Http\Controllers\Storefront\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        // Resolve by SEO URL or numeric id
        $category = is_numeric($slug)
            ? Category::with('description', 'children.description', 'paths')->findOrFail($slug)
            : Category::whereHas('seoUrl', fn($q) => $q->where('keyword', $slug))->with('description')->firstOrFail();

        $query = Product::with('description', 'images', 'specials')
            ->whereHas('categories', fn($q) => $q->where('category_id', $category->id))
            ->active();

        // Sorting
        $sort  = $request->input('sort', 'p.sort_order');
        $order = $request->input('order', 'ASC');
        $query->orderBy(match ($sort) {
            'name'  => 'products.id', // resolved via description join
            'price' => 'price',
            'rating'=> 'viewed',
            default => 'sort_order',
        }, $order);

        $products = $query->paginate($request->input('limit', 15));

        return view('storefront.catalog.category', compact('category', 'products'));
    }
}
