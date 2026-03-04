@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div style="font-size: 24px;">⏳</div>
                <div class="value">{{ $pendingRequests }}</div>
                <h3>Pending Requests</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div style="font-size: 24px;">✅</div>
                <div class="value">{{ $approvedRequests }}</div>
                <h3>Approved Requests</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div style="font-size: 24px;">❌</div>
                <div class="value">{{ $rejectedRequests }}</div>
                <h3>Rejected Requests</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div style="font-size: 24px;">📦</div>
                <div class="value">{{ $totalEquipment }}</div>
                <h3>Total Equipment</h3>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div style="font-size: 24px;">👥</div>
                <div class="value">{{ $totalUsers }}</div>
                <h3>Total Users</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div style="font-size: 24px;">✓</div>
                <div class="value">{{ $activeUsers }}</div>
                <h3>Active Users</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary me-2">➕ Create User</a>
                    <a href="{{ route('equipment.create') }}" class="btn btn-success me-2">➕ Add Equipment</a>
                    <a href="{{ route('requests.index') }}" class="btn btn-info">✅ Review Requests</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
