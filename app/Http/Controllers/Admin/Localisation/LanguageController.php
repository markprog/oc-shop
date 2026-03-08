<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function index(): View
    {
        $languages = Language::orderBy('name')->paginate(20);
        return view('admin.localisation.language.index', compact('languages'));
    }

    public function create(): View
    {
        return view('admin.localisation.language.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:32'],
            'code'       => ['required', 'string', 'max:5', 'unique:languages,code'],
            'locale'     => ['required', 'string', 'max:255'],
            'image'      => ['nullable', 'string', 'max:64'],
            'directory'  => ['required', 'string', 'max:32'],
            'sort_order' => ['required', 'integer'],
            'status'     => ['boolean'],
        ]);

        Language::create($request->only('name', 'code', 'locale', 'image', 'directory', 'sort_order', 'status'));

        return redirect()->route('admin.localisation.language.index')->with('success', 'Language added.');
    }

    public function edit(Language $language): View
    {
        return view('admin.localisation.language.form', compact('language'));
    }

    public function update(Request $request, Language $language): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:32'],
            'code'       => ['required', 'string', 'max:5', 'unique:languages,code,' . $language->language_id . ',language_id'],
            'locale'     => ['required', 'string', 'max:255'],
            'directory'  => ['required', 'string', 'max:32'],
            'sort_order' => ['required', 'integer'],
        ]);

        $language->update($request->only('name', 'code', 'locale', 'image', 'directory', 'sort_order', 'status'));

        return redirect()->route('admin.localisation.language.index')->with('success', 'Language updated.');
    }

    public function destroy(Language $language): RedirectResponse
    {
        $language->delete();
        return redirect()->route('admin.localisation.language.index')->with('success', 'Language deleted.');
    }
}
