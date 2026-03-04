@extends('layouts.app')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">⏳</div>
                <div class="value">{{ $pendingRequests }}</div>
                <h3>Pending Requests</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">✅</div>
                <div class="value">{{ $approvedRequests }}</div>
                <h3>Approved Requests</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">📦</div>
                <div class="value">{{ $availableEquipment }}/{{ $totalEquipment }}</div>
                <h3>Available Equipment</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Recent Requests</h5>
                </div>
                <div class="card-body">
                    @if($recentRequests->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Equipment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $req)
                                    <tr>
                                        <td>{{ $req->requester->name }}</td>
                                        <td>{{ $req->equipment->name }}</td>
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
                    @else
                        <p class="text-muted">No recent requests</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('equipment.create') }}" class="btn btn-success btn-sm w-100 mb-2">➕ Add Equipment</a>
                    <a href="{{ route('requests.index') }}" class="btn btn-info btn-sm w-100 mb-2">✅ Approve Requests</a>
                    <a href="{{ route('equipment.index') }}" class="btn btn-primary btn-sm w-100">📋 View Equipment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
