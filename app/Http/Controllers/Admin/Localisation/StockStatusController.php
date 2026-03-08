<?php

namespace App\Http\Controllers\Admin\Localisation;

use App\Http\Controllers\Controller;
use App\Models\StockStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockStatusController extends Controller
{
    public function index(): View
    {
        $statuses = StockStatus::orderBy('name')->paginate(20);
        return view('admin.localisation.stock-status.index', compact('statuses'));
    }

    public function create(): View
    {
        return view('admin.localisation.stock-status.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        StockStatus::create(['name' => $request->name]);
        return redirect()->route('admin.localisation.stock-status.index')->with('success', 'Stock status added.');
    }

    public function edit(StockStatus $stockStatus): View
    {
        return view('admin.localisation.stock-status.form', compact('stockStatus'));
    }

    public function update(Request $request, StockStatus $stockStatus): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:32']]);
        $stockStatus->update(['name' => $request->name]);
        return redirect()->route('admin.localisation.stock-status.index')->with('success', 'Stock status updated.');
    }

    public function destroy(StockStatus $stockStatus): RedirectResponse
    {
        $stockStatus->delete();
        return redirect()->route('admin.localisation.stock-status.index')->with('success', 'Stock status deleted.');
    }
}
