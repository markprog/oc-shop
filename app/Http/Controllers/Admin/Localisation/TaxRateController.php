<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\CustomerGroup;
use App\Models\GeoZone;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    public function index(): View
    {
        $taxRates = TaxRate::with('geoZone')->orderBy('name')->paginate(20);
        return view('admin.localisation.tax-rate.index', compact('taxRates'));
    }

    public function create(): View
    {
        $geoZones       = GeoZone::orderBy('name')->get();
        $customerGroups = CustomerGroup::with('description')->get();
        return view('admin.localisation.tax-rate.form', compact('geoZones', 'customerGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:32'],
            'rate'        => ['required', 'numeric'],
            'type'        => ['required', 'in:P,F'],
            'geo_zone_id' => ['required', 'exists:geo_zones,geo_zone_id'],
        ]);

        $taxRate = TaxRate::create($request->only('name', 'rate', 'type', 'geo_zone_id'));
        $taxRate->customerGroups()->sync($request->input('customer_group_ids', []));

        return redirect()->route('admin.localisation.tax-rate.index')->with('success', 'Tax rate added.');
    }

    public function edit(TaxRate $taxRate): View
    {
        $taxRate->load('customerGroups');
        $geoZones       = GeoZone::orderBy('name')->get();
        $customerGroups = CustomerGroup::with('description')->get();
        return view('admin.localisation.tax-rate.form', compact('taxRate', 'geoZones', 'customerGroups'));
    }

    public function update(Request $request, TaxRate $taxRate): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:32'],
            'rate'        => ['required', 'numeric'],
            'type'        => ['required', 'in:P,F'],
            'geo_zone_id' => ['required', 'exists:geo_zones,geo_zone_id'],
        ]);

        $taxRate->update($request->only('name', 'rate', 'type', 'geo_zone_id'));
        $taxRate->customerGroups()->sync($request->input('customer_group_ids', []));

        return redirect()->route('admin.localisation.tax-rate.index')->with('success', 'Tax rate updated.');
    }

    public function destroy(TaxRate $taxRate): RedirectResponse
    {
        $taxRate->delete();
        return redirect()->route('admin.localisation.tax-rate.index')->with('success', 'Tax rate deleted.');
    }
}
