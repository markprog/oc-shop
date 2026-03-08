<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttributeGroupController extends Controller
{
    public function index(): View
    {
        $groups = AttributeGroup::with('description')->orderBy('sort_order')->paginate(20);
        return view('admin.catalog.attribute-group.index', compact('groups'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.attribute-group.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['sort_order' => ['required', 'integer']]);

        $group = AttributeGroup::create(['sort_order' => $request->sort_order]);

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $group->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.attribute-group.index')->with('success', 'Attribute group added.');
    }

    public function edit(AttributeGroup $attributeGroup): View
    {
        $attributeGroup->load('descriptions');
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.attribute-group.form', compact('attributeGroup', 'languages'));
    }

    public function update(Request $request, AttributeGroup $attributeGroup): RedirectResponse
    {
        $request->validate(['sort_order' => ['required', 'integer']]);

        $attributeGroup->update(['sort_order' => $request->sort_order]);

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $attributeGroup->descriptions()->updateOrCreate(
                ['language_id' => $langId],
                ['name' => $desc['name']]
            );
        }

        return redirect()->route('admin.catalog.attribute-group.index')->with('success', 'Attribute group updated.');
    }

    public function destroy(AttributeGroup $attributeGroup): RedirectResponse
    {
        $attributeGroup->delete();
        return redirect()->route('admin.catalog.attribute-group.index')->with('success', 'Attribute group deleted.');
    }
}
