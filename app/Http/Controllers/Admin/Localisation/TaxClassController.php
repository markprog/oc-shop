<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\GeoZone;
use App\Models\TaxClass;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxClassController extends Controller
{
    public function index(): View
    {
        $taxClasses = TaxClass::orderBy('title')->paginate(20);
        return view('admin.localisation.tax-class.index', compact('taxClasses'));
    }

    public function create(): View
    {
        $taxRates = TaxRate::orderBy('name')->get();
        $geoZones = GeoZone::orderBy('name')->get();
        return view('admin.localisation.tax-class.form', compact('taxRates', 'geoZones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
        ]);

        $taxClass = TaxClass::create($request->only('title', 'description'));

        foreach ($request->input('rules', []) as $rule) {
            $taxClass->rules()->create([
                'tax_rate_id' => $rule['tax_rate_id'],
                'based'       => $rule['based'],
                'priority'    => $rule['priority'] ?? 1,
            ]);
        }

        return redirect()->route('admin.localisation.tax-class.index')->with('success', 'Tax class added.');
    }

    public function edit(TaxClass $taxClass): View
    {
        $taxClass->load('rules.rate');
        $taxRates = TaxRate::orderBy('name')->get();
        $geoZones = GeoZone::orderBy('name')->get();
        return view('admin.localisation.tax-class.form', compact('taxClass', 'taxRates', 'geoZones'));
    }

    public function update(Request $request, TaxClass $taxClass): RedirectResponse
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
        ]);

        $taxClass->update($request->only('title', 'description'));

        $taxClass->rules()->delete();
        foreach ($request->input('rules', []) as $rule) {
            $taxClass->rules()->create([
                'tax_rate_id' => $rule['tax_rate_id'],
                'based'       => $rule['based'],
                'priority'    => $rule['priority'] ?? 1,
            ]);
        }

        return redirect()->route('admin.localisation.tax-class.index')->with('success', 'Tax class updated.');
    }

    public function destroy(TaxClass $taxClass): RedirectResponse
    {
        $taxClass->rules()->delete();
        $taxClass->delete();
        return redirect()->route('admin.localisation.tax-class.index')->with('success', 'Tax class deleted.');
    }
}
