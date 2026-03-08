<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Models\FilterGroup;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FilterController extends Controller
{
    public function index(): View
    {
        $filters = Filter::with('description', 'group.description')->orderBy('sort_order')->paginate(20);
        return view('admin.catalog.filter.index', compact('filters'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        $groups    = FilterGroup::with('description')->get();
        return view('admin.catalog.filter.form', compact('languages', 'groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'filter_group_id' => ['required', 'exists:filter_groups,filter_group_id'],
            'sort_order'      => ['required', 'integer'],
        ]);

        $filter = Filter::create($request->only('filter_group_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $filter->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.filter.index')->with('success', 'Filter added.');
    }

    public function edit(Filter $filter): View
    {
        $filter->load('descriptions');
        $languages = Language::where('status', true)->get();
        $groups    = FilterGroup::with('description')->get();
        return view('admin.catalog.filter.form', compact('filter', 'languages', 'groups'));
    }

    public function update(Request $request, Filter $filter): RedirectResponse
    {
        $request->validate([
            'filter_group_id' => ['required', 'exists:filter_groups,filter_group_id'],
            'sort_order'      => ['required', 'integer'],
        ]);

        $filter->update($request->only('filter_group_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $filter->descriptions()->updateOrCreate(['language_id' => $langId], ['name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.filter.index')->with('success', 'Filter updated.');
    }

    public function destroy(Filter $filter): RedirectResponse
    {
        $filter->delete();
        return redirect()->route('admin.catalog.filter.index')->with('success', 'Filter deleted.');
    }
}
