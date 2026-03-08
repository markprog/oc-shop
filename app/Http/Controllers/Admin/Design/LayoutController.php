<?php

namespace App\Http\Controllers\Admin\Design;

use App\Http\Controllers\Controller;
use App\Models\Layout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayoutController extends Controller
{
    public function index(): View
    {
        $layouts = Layout::orderBy('name')->paginate(20);
        return view('admin.design.layout.index', compact('layouts'));
    }

    public function create(): View
    {
        return view('admin.design.layout.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:64']]);

        $layout = Layout::create(['name' => $request->name]);

        foreach ($request->input('modules', []) as $mod) {
            $layout->modules()->create($mod);
        }

        foreach ($request->input('routes', []) as $route) {
            $layout->routes()->create(['route' => $route]);
        }

        return redirect()->route('admin.design.layout.index')->with('success', 'Layout added.');
    }

    public function edit(Layout $layout): View
    {
        $layout->load('modules', 'routes');
        return view('admin.design.layout.form', compact('layout'));
    }

    public function update(Request $request, Layout $layout): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:64']]);

        $layout->update(['name' => $request->name]);

        $layout->modules()->delete();
        foreach ($request->input('modules', []) as $mod) {
            $layout->modules()->create($mod);
        }

        $layout->routes()->delete();
        foreach ($request->input('routes', []) as $route) {
            $layout->routes()->create(['route' => $route]);
        }

        return redirect()->route('admin.design.layout.index')->with('success', 'Layout updated.');
    }

    public function destroy(Layout $layout): RedirectResponse
    {
        $layout->modules()->delete();
        $layout->routes()->delete();
        $layout->delete();
        return redirect()->route('admin.design.layout.index')->with('success', 'Layout deleted.');
    }
}
