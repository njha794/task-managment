<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private function authorizeSuperAdmin(Request $request): void
    {
        if (! $request->user()?->hasRole('Super Admin')) {
            abort(403, 'Unauthorized.');
        }
    }

    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin($request);
        $users = User::with('roles')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorizeSuperAdmin($request);
        $roles = \Spatie\Permission\Models\Role::where('guard_name', 'web')->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeSuperAdmin($request);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->update($request->only('name', 'email'));
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorizeSuperAdmin($request);
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
