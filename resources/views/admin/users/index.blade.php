@extends('layouts.app')

@section('page-title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">➕ Create New User</a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Users</h5>
        </div>
        <div class="card-body">
            @if($users->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @if($user->id !== Auth::id())
                                            @if($user->is_active)
                                                <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deactivate this user?')">Deactivate</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $users->links('pagination::bootstrap-4') }}
            @else
                <div class="alert alert-info">No users found</div>
            @endif
        </div>
    </div>
</div>
@endsection
