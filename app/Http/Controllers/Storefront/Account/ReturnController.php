<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\ReturnReason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        $returns = auth('web')->user()->returns()->with('returnStatus', 'returnReason')->latest()->paginate(10);
        return view('storefront.account.returns.index', compact('returns'));
    }

    public function create(): View
    {
        $reasons = ReturnReason::all();
        return view('storefront.account.returns.create', compact('reasons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'order_id'         => ['required', 'integer'],
            'product_name'     => ['required', 'string', 'max:255'],
            'model'            => ['required', 'string', 'max:64'],
            'quantity'         => ['required', 'integer', 'min:1'],
            'return_reason_id' => ['required', 'exists:return_reasons,return_reason_id'],
            'comment'          => ['nullable', 'string'],
        ]);

        auth('web')->user()->returns()->create(array_merge($request->validated(), [
            'firstname'         => auth('web')->user()->firstname,
            'lastname'          => auth('web')->user()->lastname,
            'email'             => auth('web')->user()->email,
            'telephone'         => auth('web')->user()->telephone,
            'return_status_id'  => 1,
        ]));

        return redirect()->route('account.return.index')->with('success', 'Return request submitted.');
    }

    public function show(int $id): View
    {
        $return = auth('web')->user()->returns()->with('returnStatus', 'returnReason', 'histories.status')->findOrFail($id);
        return view('storefront.account.returns.show', compact('return'));
    }
}
