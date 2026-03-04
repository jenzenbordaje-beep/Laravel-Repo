<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function indexUsers()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $users = User::paginate(15);

        return view('admin.users.index', ['users' => $users]);
    }

    public function createUser()
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,employee',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Created user: {$user->name} with role {$user->role}",
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function editUser(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        return view('admin.users.edit', ['user' => $user]);
    }

    public function updateUser(Request $request, User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|in:super_admin,admin,employee',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Updated user: {$user->name}",
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function deactivateUser(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        // Prevent deactivating self
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account');
        }

        $user->update(['is_active' => false]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deactivate',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Deactivated user: {$user->name}",
        ]);

        return back()->with('success', 'User deactivated successfully');
    }

    public function activateUser(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $user->update(['is_active' => true]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'activate',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'description' => "Activated user: {$user->name}",
        ]);

        return back()->with('success', 'User activated successfully');
    }
}
