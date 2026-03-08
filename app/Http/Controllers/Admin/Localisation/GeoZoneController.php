<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\GeoZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeoZoneController extends Controller
{
    public function index(): View
    {
        $geoZones = GeoZone::orderBy('name')->paginate(20);
        return view('admin.localisation.geo-zone.index', compact('geoZones'));
    }

    public function create(): View
    {
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('admin.localisation.geo-zone.form', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
        ]);

        $geoZone = GeoZone::create($request->only('name', 'description'));

        foreach ($request->input('zones', []) as $zone) {
            $geoZone->zones()->create([
                'country_id' => $zone['country_id'],
                'zone_id'    => $zone['zone_id'] ?? 0,
            ]);
        }

        return redirect()->route('admin.localisation.geo-zone.index')->with('success', 'Geo zone added.');
    }

    public function edit(GeoZone $geoZone): View
    {
        $geoZone->load('zones.country', 'zones.zone');
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('admin.localisation.geo-zone.form', compact('geoZone', 'countries'));
    }

    public function update(Request $request, GeoZone $geoZone): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string'],
        ]);

        $geoZone->update($request->only('name', 'description'));

        $geoZone->zones()->delete();
        foreach ($request->input('zones', []) as $zone) {
            $geoZone->zones()->create([
                'country_id' => $zone['country_id'],
                'zone_id'    => $zone['zone_id'] ?? 0,
            ]);
        }

        return redirect()->route('admin.localisation.geo-zone.index')->with('success', 'Geo zone updated.');
    }

    public function destroy(GeoZone $geoZone): RedirectResponse
    {
        $geoZone->zones()->delete();
        $geoZone->delete();
        return redirect()->route('admin.localisation.geo-zone.index')->with('success', 'Geo zone deleted.');
    }
}
