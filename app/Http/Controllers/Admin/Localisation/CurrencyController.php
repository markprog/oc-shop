<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrencyController extends Controller
{
    public function index(): View
    {
        $currencies = Currency::orderBy('title')->paginate(20);
        return view('admin.localisation.currency.index', compact('currencies'));
    }

    public function create(): View
    {
        return view('admin.localisation.currency.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:32'],
            'code'          => ['required', 'string', 'size:3', 'unique:currencies,code'],
            'symbol_left'   => ['nullable', 'string', 'max:12'],
            'symbol_right'  => ['nullable', 'string', 'max:12'],
            'decimal_place' => ['required', 'integer', 'min:0', 'max:4'],
            'value'         => ['required', 'numeric', 'min:0'],
            'status'        => ['boolean'],
        ]);

        Currency::create($request->only('title', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'decimal_point', 'thousand_point', 'value', 'status'));

        return redirect()->route('admin.localisation.currency.index')->with('success', 'Currency added.');
    }

    public function edit(Currency $currency): View
    {
        return view('admin.localisation.currency.form', compact('currency'));
    }

    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $request->validate([
            'title'         => ['required', 'string', 'max:32'],
            'code'          => ['required', 'string', 'size:3', 'unique:currencies,code,' . $currency->currency_id . ',currency_id'],
            'decimal_place' => ['required', 'integer', 'min:0', 'max:4'],
            'value'         => ['required', 'numeric', 'min:0'],
        ]);

        $currency->update($request->only('title', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'decimal_point', 'thousand_point', 'value', 'status'));

        return redirect()->route('admin.localisation.currency.index')->with('success', 'Currency updated.');
    }

    public function destroy(Currency $currency): RedirectResponse
    {
        $currency->delete();
        return redirect()->route('admin.localisation.currency.index')->with('success', 'Currency deleted.');
    }
}
