<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Option;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OptionController extends Controller
{
    public function index(): View
    {
        $options = Option::with('description')->orderBy('sort_order')->paginate(20);
        return view('admin.catalog.option.index', compact('options'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.option.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type'       => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
        ]);

        $option = Option::create($request->only('type', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $option->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        foreach ($request->input('values', []) as $val) {
            $optionValue = $option->values()->create(['image' => $val['image'] ?? null, 'sort_order' => $val['sort_order'] ?? 0]);
            foreach ($val['descriptions'] ?? [] as $langId => $vDesc) {
                $optionValue->descriptions()->create(['language_id' => $langId, 'name' => $vDesc['name']]);
            }
        }

        return redirect()->route('admin.catalog.option.index')->with('success', 'Option added.');
    }

    public function edit(Option $option): View
    {
        $option->load('descriptions', 'values.descriptions');
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.option.form', compact('option', 'languages'));
    }

    public function update(Request $request, Option $option): RedirectResponse
    {
        $request->validate([
            'type'       => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
        ]);

        $option->update($request->only('type', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $option->descriptions()->updateOrCreate(['language_id' => $langId], ['name' => $desc['name']]);
        }

        // Resync values
        $option->values()->each(fn($v) => $v->descriptions()->delete());
        $option->values()->delete();

        foreach ($request->input('values', []) as $val) {
            $optionValue = $option->values()->create(['image' => $val['image'] ?? null, 'sort_order' => $val['sort_order'] ?? 0]);
            foreach ($val['descriptions'] ?? [] as $langId => $vDesc) {
                $optionValue->descriptions()->create(['language_id' => $langId, 'name' => $vDesc['name']]);
            }
        }

        return redirect()->route('admin.catalog.option.index')->with('success', 'Option updated.');
    }

    public function destroy(Option $option): RedirectResponse
    {
        $option->values()->each(fn($v) => $v->descriptions()->delete());
        $option->values()->delete();
        $option->descriptions()->delete();
        $option->delete();
        return redirect()->route('admin.catalog.option.index')->with('success', 'Option deleted.');
    }
}
