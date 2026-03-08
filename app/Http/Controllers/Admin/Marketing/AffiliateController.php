<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Affiliate::query();

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstname', 'like', '%' . $request->name . '%')
                  ->orWhere('lastname', 'like', '%' . $request->name . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $affiliates = $query->orderByDesc('affiliate_id')->paginate(20)->withQueryString();

        return view('admin.marketing.affiliate.index', compact('affiliates'));
    }

    public function show(Affiliate $affiliate): View
    {
        $affiliate->load('transactions');
        return view('admin.marketing.affiliate.show', compact('affiliate'));
    }

    public function approve(Affiliate $affiliate): RedirectResponse
    {
        $affiliate->update(['status' => true]);
        return back()->with('success', 'Affiliate approved.');
    }

    public function destroy(Affiliate $affiliate): RedirectResponse
    {
        $affiliate->delete();
        return redirect()->route('admin.marketing.affiliate.index')->with('success', 'Affiliate deleted.');
    }
}
