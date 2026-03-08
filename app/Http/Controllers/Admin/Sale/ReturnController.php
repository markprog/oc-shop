<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\ReturnReason;
use App\Models\ReturnStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductReturn::with('returnStatus', 'returnReason');

        if ($request->filled('return_id')) {
            $query->where('return_id', $request->return_id);
        }
        if ($request->filled('status_id')) {
            $query->where('return_status_id', $request->status_id);
        }

        $returns  = $query->orderByDesc('date_added')->paginate(20)->withQueryString();
        $statuses = ReturnStatus::all();

        return view('admin.sale.return.index', compact('returns', 'statuses'));
    }

    public function show(ProductReturn $return): View
    {
        $return->load('histories.status', 'returnReason', 'returnStatus');
        $statuses = ReturnStatus::all();
        return view('admin.sale.return.show', compact('return', 'statuses'));
    }

    public function addHistory(Request $request, ProductReturn $return): RedirectResponse
    {
        $request->validate([
            'return_status_id' => ['required', 'exists:return_statuses,return_status_id'],
            'comment'          => ['nullable', 'string'],
            'notify'           => ['boolean'],
        ]);

        $return->update(['return_status_id' => $request->return_status_id]);

        $return->histories()->create([
            'return_status_id' => $request->return_status_id,
            'notify'           => $request->boolean('notify'),
            'comment'          => $request->comment ?? '',
        ]);

        return back()->with('success', 'Return history added.');
    }

    public function destroy(ProductReturn $return): RedirectResponse
    {
        $return->delete();
        return redirect()->route('admin.sale.return.index')->with('success', 'Return deleted.');
    }
}
