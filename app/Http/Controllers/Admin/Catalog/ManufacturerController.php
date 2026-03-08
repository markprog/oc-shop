<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Manufacturer::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $manufacturers = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.catalog.manufacturer.index', compact('manufacturers'));
    }

    public function create(): View
    {
        return view('admin.catalog.manufacturer.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:64'],
            'image'      => ['nullable', 'string'],
            'sort_order' => ['required', 'integer'],
        ]);

        Manufacturer::create($request->only('name', 'image', 'sort_order'));

        return redirect()->route('admin.catalog.manufacturer.index')->with('success', 'Manufacturer added.');
    }

    public function edit(Manufacturer $manufacturer): View
    {
        return view('admin.catalog.manufacturer.form', compact('manufacturer'));
    }

    public function update(Request $request, Manufacturer $manufacturer): RedirectResponse
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:64'],
            'image'      => ['nullable', 'string'],
            'sort_order' => ['required', 'integer'],
        ]);

        $manufacturer->update($request->only('name', 'image', 'sort_order'));

        return redirect()->route('admin.catalog.manufacturer.index')->with('success', 'Manufacturer updated.');
    }

    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        $manufacturer->delete();
        return redirect()->route('admin.catalog.manufacturer.index')->with('success', 'Manufacturer deleted.');
    }
}
