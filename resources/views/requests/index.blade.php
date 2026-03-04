@extends('layouts.app')

@section('page-title', 'Equipment Requests')

@section('content')
<div class="container-fluid">
    @if(Auth::user()->isEmployee())
        <div class="mb-3">
            <a href="{{ route('requests.create') }}" class="btn btn-primary">➕ Request Equipment</a>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Requests</h5>
        </div>
        <div class="card-body">
            @if($requests->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Equipment</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td>{{ $req->requester->name }}</td>
                                    <td>{{ $req->equipment->name }}</td>
                                    <td>{{ Str::limit($req->reason, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $req->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('requests.show', $req) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $requests->links('pagination::bootstrap-4') }}
            @else
                <div class="alert alert-info">No requests found</div>
            @endif
        </div>
    </div>
</div>
@endsection
