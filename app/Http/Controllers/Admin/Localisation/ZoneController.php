<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ZoneController extends Controller
{
    public function index(): View
    {
        $zones = Zone::with('country')->orderBy('name')->paginate(20);
        return view('admin.localisation.zone.index', compact('zones'));
    }

    public function create(): View
    {
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('admin.localisation.zone.form', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'country_id' => ['required', 'exists:countries,country_id'],
            'name'       => ['required', 'string', 'max:128'],
            'code'       => ['required', 'string', 'max:32'],
            'status'     => ['boolean'],
        ]);

        Zone::create($request->only('country_id', 'name', 'code', 'status'));

        return redirect()->route('admin.localisation.zone.index')->with('success', 'Zone added.');
    }

    public function edit(Zone $zone): View
    {
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('admin.localisation.zone.form', compact('zone', 'countries'));
    }

    public function update(Request $request, Zone $zone): RedirectResponse
    {
        $request->validate([
            'country_id' => ['required', 'exists:countries,country_id'],
            'name'       => ['required', 'string', 'max:128'],
            'code'       => ['required', 'string', 'max:32'],
        ]);

        $zone->update($request->only('country_id', 'name', 'code', 'status'));

        return redirect()->route('admin.localisation.zone.index')->with('success', 'Zone updated.');
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        $zone->delete();
        return redirect()->route('admin.localisation.zone.index')->with('success', 'Zone deleted.');
    }
}
