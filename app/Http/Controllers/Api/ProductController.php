<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('description', 'images')
            ->where('status', true);

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn($q) => $q->where('categories.category_id', $request->category_id));
        }
        if ($request->filled('manufacturer_id')) {
            $query->where('manufacturer_id', $request->manufacturer_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('description', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'name_asc'   => $query->orderBy('model'),
                default      => $query->orderByDesc('date_added'),
            };
        } else {
            $query->orderByDesc('date_added');
        }

        $products = $query->paginate($request->input('limit', 20));

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with('description', 'images', 'options.description', 'options.values.description', 'manufacturer')
            ->where('status', true)
            ->findOrFail($id);

        return response()->json($product);
    }
}
