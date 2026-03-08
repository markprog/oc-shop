<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserGroupController extends Controller
{
    public function index(): View
    {
        $groups = UserGroup::orderBy('name')->paginate(20);
        return view('admin.user.user-group.index', compact('groups'));
    }

    public function create(): View
    {
        return view('admin.user.user-group.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:64']]);

        UserGroup::create([
            'name'       => $request->name,
            'permission' => [
                'access' => $request->input('permission.access', []),
                'modify' => $request->input('permission.modify', []),
            ],
        ]);

        return redirect()->route('admin.user.user-group.index')->with('success', 'User group added.');
    }

    public function edit(UserGroup $userGroup): View
    {
        return view('admin.user.user-group.form', compact('userGroup'));
    }

    public function update(Request $request, UserGroup $userGroup): RedirectResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:64']]);

        $userGroup->update([
            'name'       => $request->name,
            'permission' => [
                'access' => $request->input('permission.access', []),
                'modify' => $request->input('permission.modify', []),
            ],
        ]);

        return redirect()->route('admin.user.user-group.index')->with('success', 'User group updated.');
    }

    public function destroy(UserGroup $userGroup): RedirectResponse
    {
        $userGroup->delete();
        return redirect()->route('admin.user.user-group.index')->with('success', 'User group deleted.');
    }
}
