<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerGroupController extends Controller
{
    public function index(): View
    {
        $groups = CustomerGroup::with('description')->orderBy('sort_order')->paginate(20);
        return view('admin.customer.customer-group.index', compact('groups'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.customer.customer-group.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'approval'    => ['boolean'],
            'company_id'  => ['boolean'],
            'tax_id'      => ['boolean'],
            'sort_order'  => ['required', 'integer'],
        ]);

        $group = CustomerGroup::create($request->only('approval', 'company_id', 'tax_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $group->descriptions()->create(['language_id' => $langId, 'name' => $desc['name'], 'description' => $desc['description'] ?? '']);
        }

        return redirect()->route('admin.customer.customer-group.index')->with('success', 'Customer group added.');
    }

    public function edit(CustomerGroup $customerGroup): View
    {
        $customerGroup->load('descriptions');
        $languages = Language::where('status', true)->get();
        return view('admin.customer.customer-group.form', compact('customerGroup', 'languages'));
    }

    public function update(Request $request, CustomerGroup $customerGroup): RedirectResponse
    {
        $request->validate(['sort_order' => ['required', 'integer']]);

        $customerGroup->update($request->only('approval', 'company_id', 'tax_id', 'sort_order'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $customerGroup->descriptions()->updateOrCreate(
                ['language_id' => $langId],
                ['name' => $desc['name'], 'description' => $desc['description'] ?? '']
            );
        }

        return redirect()->route('admin.customer.customer-group.index')->with('success', 'Customer group updated.');
    }

    public function destroy(CustomerGroup $customerGroup): RedirectResponse
    {
        $customerGroup->delete();
        return redirect()->route('admin.customer.customer-group.index')->with('success', 'Customer group deleted.');
    }
}
