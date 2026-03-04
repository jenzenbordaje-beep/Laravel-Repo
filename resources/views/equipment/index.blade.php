@extends('layouts.app')

@section('page-title', 'Equipment')

@section('content')
<div class="container-fluid">
    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
        <div class="mb-3">
            <a href="{{ route('equipment.create') }}" class="btn btn-primary">➕ Add New Equipment</a>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Equipment List</h5>
        </div>
        <div class="card-body">
            @if($equipment->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Serial Number</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipment as $item)
                                <tr>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>{{ $item->serial_number }}</td>
                                    <td>
                                        <span class="badge bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'in_use' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->assignedUser)
                                            {{ $item->assignedUser->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($item->description, 50) ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('equipment.show', $item) }}" class="btn btn-sm btn-info">View</a>
                                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                            <a href="{{ route('equipment.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('equipment.archive', $item) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this equipment?')">Archive</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $equipment->links('pagination::bootstrap-4') }}
            @else
                <div class="alert alert-info">No equipment found</div>
            @endif
        </div>
    </div>
</div>
@endsection
