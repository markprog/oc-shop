<?php

namespace App\Http\Controllers\Admin\Design;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderByDesc('banner_id')->paginate(20);
        return view('admin.design.banner.index', compact('banners'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.design.banner.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:64'],
            'status'     => ['boolean'],
        ]);

        $banner = Banner::create($request->only('name', 'status'));

        foreach ($request->input('images', []) as $langId => $imgs) {
            foreach ($imgs as $img) {
                $banner->images()->create([
                    'language_id' => $langId,
                    'title'       => $img['title'],
                    'link'        => $img['link'],
                    'image'       => $img['image'],
                    'sort_order'  => $img['sort_order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.design.banner.index')->with('success', 'Banner added.');
    }

    public function edit(Banner $banner): View
    {
        $banner->load('images');
        $languages = Language::where('status', true)->get();
        return view('admin.design.banner.form', compact('banner', 'languages'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $request->validate([
            'name'   => ['required', 'string', 'max:64'],
            'status' => ['boolean'],
        ]);

        $banner->update($request->only('name', 'status'));

        $banner->images()->delete();

        foreach ($request->input('images', []) as $langId => $imgs) {
            foreach ($imgs as $img) {
                $banner->images()->create([
                    'language_id' => $langId,
                    'title'       => $img['title'],
                    'link'        => $img['link'],
                    'image'       => $img['image'],
                    'sort_order'  => $img['sort_order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.design.banner.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->images()->delete();
        $banner->delete();
        return redirect()->route('admin.design.banner.index')->with('success', 'Banner deleted.');
    }
}
