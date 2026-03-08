<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\AddressRequest;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $addresses = auth('web')->user()->addresses()->with('country', 'zone')->get();
        return view('storefront.account.address.index', compact('addresses'));
    }

    public function create(): View
    {
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('storefront.account.address.create', compact('countries'));
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        auth('web')->user()->addresses()->create($request->validated());
        return redirect()->route('account.address.index')->with('success', 'Address added.');
    }

    public function edit(Address $address): View
    {
        $this->authorize('update', $address);
        $countries = Country::where('status', true)->orderBy('name')->get();
        return view('storefront.account.address.edit', compact('address', 'countries'));
    }

    public function update(AddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);
        $address->update($request->validated());
        return redirect()->route('account.address.index')->with('success', 'Address updated.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);
        $address->delete();
        return redirect()->route('account.address.index')->with('success', 'Address deleted.');
    }
}
