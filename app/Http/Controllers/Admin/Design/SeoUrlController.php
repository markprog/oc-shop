<?php

namespace App\Http\Controllers\Admin\Design;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SeoUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoUrlController extends Controller
{
    public function index(Request $request): View
    {
        $query = SeoUrl::with('language');

        if ($request->filled('keyword')) {
            $query->where('keyword', 'like', '%' . $request->keyword . '%');
        }
        if ($request->filled('query')) {
            $query->where('query', 'like', '%' . $request->query . '%');
        }

        $seoUrls   = $query->orderByDesc('seo_url_id')->paginate(20)->withQueryString();
        $languages = Language::where('status', true)->get();

        return view('admin.design.seo-url.index', compact('seoUrls', 'languages'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.design.seo-url.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'store_id'    => ['required', 'integer'],
            'language_id' => ['required', 'exists:languages,language_id'],
            'query'       => ['required', 'string'],
            'keyword'     => ['required', 'string', 'max:255', 'unique:seo_urls,keyword'],
        ]);

        SeoUrl::create($request->only('store_id', 'language_id', 'query', 'keyword'));

        return redirect()->route('admin.design.seo-url.index')->with('success', 'SEO URL added.');
    }

    public function edit(SeoUrl $seoUrl): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.design.seo-url.form', compact('seoUrl', 'languages'));
    }

    public function update(Request $request, SeoUrl $seoUrl): RedirectResponse
    {
        $request->validate([
            'store_id'    => ['required', 'integer'],
            'language_id' => ['required', 'exists:languages,language_id'],
            'query'       => ['required', 'string'],
            'keyword'     => ['required', 'string', 'max:255', 'unique:seo_urls,keyword,' . $seoUrl->seo_url_id . ',seo_url_id'],
        ]);

        $seoUrl->update($request->only('store_id', 'language_id', 'query', 'keyword'));

        return redirect()->route('admin.design.seo-url.index')->with('success', 'SEO URL updated.');
    }

    public function destroy(SeoUrl $seoUrl): RedirectResponse
    {
        $seoUrl->delete();
        return redirect()->route('admin.design.seo-url.index')->with('success', 'SEO URL deleted.');
    }
}
