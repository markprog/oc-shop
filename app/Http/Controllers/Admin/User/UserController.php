<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('group');

        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderByDesc('user_id')->paginate(20)->withQueryString();

        return view('admin.user.user.index', compact('users'));
    }

    public function create(): View
    {
        $groups = UserGroup::orderBy('name')->get();
        return view('admin.user.user.form', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_group_id' => ['required', 'exists:user_groups,user_group_id'],
            'username'      => ['required', 'string', 'max:20', 'unique:users,username'],
            'firstname'     => ['required', 'string', 'max:32'],
            'lastname'      => ['required', 'string', 'max:32'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8'],
            'status'        => ['boolean'],
        ]);

        $data             = $request->only('user_group_id', 'username', 'firstname', 'lastname', 'email', 'status', 'image');
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.user.user.index')->with('success', 'User added.');
    }

    public function edit(User $user): View
    {
        $groups = UserGroup::orderBy('name')->get();
        return view('admin.user.user.form', compact('user', 'groups'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'user_group_id' => ['required', 'exists:user_groups,user_group_id'],
            'username'      => ['required', 'string', 'max:20', 'unique:users,username,' . $user->user_id . ',user_id'],
            'firstname'     => ['required', 'string', 'max:32'],
            'lastname'      => ['required', 'string', 'max:32'],
            'email'         => ['required', 'email', 'unique:users,email,' . $user->user_id . ',user_id'],
        ]);

        $data = $request->only('user_group_id', 'username', 'firstname', 'lastname', 'email', 'status', 'image');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.user.index')->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.user.user.index')->with('success', 'User deleted.');
    }
}
