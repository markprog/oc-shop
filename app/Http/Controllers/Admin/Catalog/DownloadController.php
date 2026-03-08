<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function index(): View
    {
        $downloads = Download::with('description')->orderByDesc('download_id')->paginate(20);
        return view('admin.catalog.download.index', compact('downloads'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.download.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'filename'   => ['required', 'string', 'max:160'],
            'mask'       => ['required', 'string', 'max:128'],
            'remaining'  => ['required', 'integer', 'min:0'],
        ]);

        $download = Download::create($request->only('filename', 'mask', 'remaining'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $download->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.download.index')->with('success', 'Download added.');
    }

    public function edit(Download $download): View
    {
        $download->load('descriptions');
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.download.form', compact('download', 'languages'));
    }

    public function update(Request $request, Download $download): RedirectResponse
    {
        $request->validate([
            'filename'  => ['required', 'string', 'max:160'],
            'mask'      => ['required', 'string', 'max:128'],
            'remaining' => ['required', 'integer', 'min:0'],
        ]);

        $download->update($request->only('filename', 'mask', 'remaining'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $download->descriptions()->updateOrCreate(['language_id' => $langId], ['name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.download.index')->with('success', 'Download updated.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        $download->delete();
        return redirect()->route('admin.catalog.download.index')->with('success', 'Download deleted.');
    }
}
