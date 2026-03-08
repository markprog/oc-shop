<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\AddressFormat;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        $countries = Country::orderBy('name')->paginate(20);
        return view('admin.localisation.country.index', compact('countries'));
    }

    public function create(): View
    {
        $formats = AddressFormat::all();
        return view('admin.localisation.country.form', compact('formats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:128'],
            'iso_code_2'       => ['required', 'string', 'size:2'],
            'iso_code_3'       => ['required', 'string', 'size:3'],
            'address_format_id'=> ['required', 'exists:address_formats,address_format_id'],
            'status'           => ['boolean'],
            'postcode_required'=> ['boolean'],
        ]);

        Country::create($request->only('name', 'iso_code_2', 'iso_code_3', 'address_format_id', 'postcode_required', 'status'));

        return redirect()->route('admin.localisation.country.index')->with('success', 'Country added.');
    }

    public function edit(Country $country): View
    {
        $formats = AddressFormat::all();
        return view('admin.localisation.country.form', compact('country', 'formats'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:128'],
            'iso_code_2'        => ['required', 'string', 'size:2'],
            'iso_code_3'        => ['required', 'string', 'size:3'],
            'address_format_id' => ['required', 'exists:address_formats,address_format_id'],
        ]);

        $country->update($request->only('name', 'iso_code_2', 'iso_code_3', 'address_format_id', 'postcode_required', 'status'));

        return redirect()->route('admin.localisation.country.index')->with('success', 'Country updated.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $country->delete();
        return redirect()->route('admin.localisation.country.index')->with('success', 'Country deleted.');
    }
}
