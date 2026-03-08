<?php

namespace App\Http\Controllers\Storefront\Cms;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $categories = ArticleCategory::with('description')->where('status', true)->orderBy('sort_order')->get();
        $articles   = Article::with('description', 'category.description')
            ->where('status', true)
            ->latest()
            ->paginate(10);

        return view('storefront.cms.blog.index', compact('categories', 'articles'));
    }

    public function category(int $categoryId): View
    {
        $category = ArticleCategory::with('description')->where('status', true)->findOrFail($categoryId);
        $articles = Article::with('description', 'category.description')
            ->where('article_category_id', $categoryId)
            ->where('status', true)
            ->latest()
            ->paginate(10);

        return view('storefront.cms.blog.category', compact('category', 'articles'));
    }

    public function show(int $id): View
    {
        $article = Article::with('description', 'category.description')
            ->where('status', true)
            ->findOrFail($id);

        return view('storefront.cms.blog.show', compact('article'));
    }
}
