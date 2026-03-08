<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\CustomerGroup;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomFieldController extends Controller
{
    public function index(): View
    {
        $fields = CustomField::with('description')->orderBy('sort_order')->paginate(20);
        return view('admin.customer.custom-field.index', compact('fields'));
    }

    public function create(): View
    {
        $languages     = Language::where('status', true)->get();
        $customerGroups = CustomerGroup::with('description')->get();
        return view('admin.customer.custom-field.form', compact('languages', 'customerGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type'        => ['required', 'string'],
            'location'    => ['required', 'string'],
            'sort_order'  => ['required', 'integer'],
            'status'      => ['boolean'],
        ]);

        $field = CustomField::create($request->only('type', 'location', 'validation', 'required', 'sort_order', 'status'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $field->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        $field->customerGroups()->sync($request->input('customer_group_ids', []));

        foreach ($request->input('values', []) as $val) {
            $fv = $field->values()->create(['sort_order' => $val['sort_order'] ?? 0]);
            foreach ($val['descriptions'] ?? [] as $langId => $vd) {
                $fv->descriptions()->create(['language_id' => $langId, 'name' => $vd['name']]);
            }
        }

        return redirect()->route('admin.customer.custom-field.index')->with('success', 'Custom field added.');
    }

    public function edit(CustomField $customField): View
    {
        $customField->load('descriptions', 'values.descriptions', 'customerGroups');
        $languages      = Language::where('status', true)->get();
        $customerGroups = CustomerGroup::with('description')->get();
        return view('admin.customer.custom-field.form', compact('customField', 'languages', 'customerGroups'));
    }

    public function update(Request $request, CustomField $customField): RedirectResponse
    {
        $request->validate([
            'type'       => ['required', 'string'],
            'location'   => ['required', 'string'],
            'sort_order' => ['required', 'integer'],
        ]);

        $customField->update($request->only('type', 'location', 'validation', 'required', 'sort_order', 'status'));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $customField->descriptions()->updateOrCreate(['language_id' => $langId], ['name' => $desc['name']]);
        }

        $customField->customerGroups()->sync($request->input('customer_group_ids', []));

        return redirect()->route('admin.customer.custom-field.index')->with('success', 'Custom field updated.');
    }

    public function destroy(CustomField $customField): RedirectResponse
    {
        $customField->delete();
        return redirect()->route('admin.customer.custom-field.index')->with('success', 'Custom field deleted.');
    }
}
