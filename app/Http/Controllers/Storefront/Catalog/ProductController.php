<?php

namespace App\Http\Controllers\Storefront\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Http\Requests\Storefront\ReviewRequest;
use App\Events\ProductReviewed;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = is_numeric($slug)
            ? Product::with([
                'description', 'images', 'identifier', 'manufacturer',
                'productOptions.option.description', 'productOptions.values.optionValue.description',
                'attributes.attribute.description',
                'related.description', 'related.images',
                'specials', 'discounts', 'reviews', 'taxClass',
            ])->active()->findOrFail($slug)
            : Product::whereHas('seoUrls', fn($q) => $q->where('keyword', $slug))
                ->with(['description', 'images', 'productOptions', 'reviews'])
                ->active()->firstOrFail();

        // Increment view count
        $product->increment('viewed');

        return view('storefront.catalog.product', compact('product'));
    }

    public function review(ReviewRequest $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);

        $review = Review::create([
            'product_id'  => $productId,
            'customer_id' => auth()->id(),
            'author'      => $request->name,
            'rating'      => $request->rating,
            'text'        => $request->text,
            'status'      => false, // pending approval
        ]);

        event(new ProductReviewed($review));

        return back()->with('success', 'Thank you for your review. It is awaiting moderation.');
    }
}
