<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('description')->orderBy('sort_order')->paginate(20);
        return view('admin.catalog.category.index', compact('categories'));
    }

    public function create(): View
    {
        $languages  = Language::where('status', true)->get();
        $categories = Category::with('description')->get();
        return view('admin.catalog.category.form', compact('languages', 'categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->categoryData());

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $category->descriptions()->create(array_merge($desc, ['language_id' => $langId]));
        }

        $category->filters()->sync($request->input('filter_ids', []));

        return redirect()->route('admin.catalog.category.index')->with('success', 'Category added.');
    }

    public function edit(Category $category): View
    {
        $category->load('descriptions', 'filters');
        $languages  = Language::where('status', true)->get();
        $categories = Category::with('description')->get();
        return view('admin.catalog.category.form', compact('category', 'languages', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->categoryData());

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $category->descriptions()->updateOrCreate(['language_id' => $langId], $desc);
        }

        $category->filters()->sync($request->input('filter_ids', []));

        return redirect()->route('admin.catalog.category.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.catalog.category.index')->with('success', 'Category deleted.');
    }
}
