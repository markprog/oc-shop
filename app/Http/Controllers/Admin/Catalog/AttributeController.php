<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttributeController extends Controller
{
    public function index(): View
    {
        $attributes = Attribute::with('description', 'group.description')->orderBy('sort_order')->paginate(20);
        return view('admin.catalog.attribute.index', compact('attributes'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        $groups    = AttributeGroup::with('description')->get();
        return view('admin.catalog.attribute.form', compact('languages', 'groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'attribute_group_id' => ['required', 'exists:attribute_groups,attribute_group_id'],
            'sort_order'         => ['required', 'integer'],
        ]);

        $attribute = Attribute::create($request->only('attribute_group_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $attribute->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.attribute.index')->with('success', 'Attribute added.');
    }

    public function edit(Attribute $attribute): View
    {
        $attribute->load('descriptions');
        $languages = Language::where('status', true)->get();
        $groups    = AttributeGroup::with('description')->get();
        return view('admin.catalog.attribute.form', compact('attribute', 'languages', 'groups'));
    }

    public function update(Request $request, Attribute $attribute): RedirectResponse
    {
        $request->validate([
            'attribute_group_id' => ['required', 'exists:attribute_groups,attribute_group_id'],
            'sort_order'         => ['required', 'integer'],
        ]);

        $attribute->update($request->only('attribute_group_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $attribute->descriptions()->updateOrCreate(
                ['language_id' => $langId],
                ['name' => $desc['name']]
            );
        }

        return redirect()->route('admin.catalog.attribute.index')->with('success', 'Attribute updated.');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        $attribute->delete();
        return redirect()->route('admin.catalog.attribute.index')->with('success', 'Attribute deleted.');
    }
}
