<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::with('group');

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'like', '%' . $request->name . '%')
                  ->orWhere('lastname', 'like', '%' . $request->name . '%');
            });
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderByDesc('date_added')->paginate(20)->withQueryString();

        return view('admin.customer.customer.index', compact('customers'));
    }

    public function create(): View
    {
        $groups = CustomerGroup::all();
        return view('admin.customer.customer.form', compact('groups'));
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data             = $request->validated();
        $data['password'] = Hash::make($data['password']);

        Customer::create($data);

        return redirect()->route('admin.customer.customer.index')->with('success', 'Customer added.');
    }

    public function edit(Customer $customer): View
    {
        $customer->load('addresses');
        $groups = CustomerGroup::all();
        return view('admin.customer.customer.form', compact('customer', 'groups'));
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customer.customer.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();
        return redirect()->route('admin.customer.customer.index')->with('success', 'Customer deleted.');
    }

    public function loginAs(Customer $customer): RedirectResponse
    {
        auth('web')->login($customer);
        return redirect('/');
    }
}
