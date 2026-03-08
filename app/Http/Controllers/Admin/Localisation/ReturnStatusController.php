<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\ReturnStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnStatusController extends Controller
{
    public function index(): View
    {
        $statuses = ReturnStatus::orderBy('name')->paginate(20);
        return view('admin.localisation.return-status.index', compact('statuses'));
    }

    public function create(): View
    {
        return view('admin.localisation.return-status.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        ReturnStatus::create(['name' => $request->name]);
        return redirect()->route('admin.localisation.return-status.index')->with('success', 'Return status added.');
    }

    public function edit(ReturnStatus $returnStatus): View
    {
        return view('admin.localisation.return-status.form', compact('returnStatus'));
    }

    public function update(Request $request, ReturnStatus $returnStatus): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        $returnStatus->update(['name' => $request->name]);
        return redirect()->route('admin.localisation.return-status.index')->with('success', 'Return status updated.');
    }

    public function destroy(ReturnStatus $returnStatus): RedirectResponse
    {
        $returnStatus->delete();
        return redirect()->route('admin.localisation.return-status.index')->with('success', 'Return status deleted.');
    }
}
