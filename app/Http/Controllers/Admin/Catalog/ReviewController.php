<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with('product.description', 'customer');

        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->orderByDesc('date_added')->paginate(20)->withQueryString();

        return view('admin.catalog.review.index', compact('reviews'));
    }

    public function edit(Review $review): View
    {
        return view('admin.catalog.review.edit', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $request->validate([
            'author' => ['required', 'string', 'max:64'],
            'text'   => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'status' => ['required', 'boolean'],
        ]);

        $review->update($request->only('author', 'text', 'rating', 'status'));

        return redirect()->route('admin.catalog.review.index')->with('success', 'Review updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.catalog.review.index')->with('success', 'Review deleted.');
    }
}
