@extends('layouts.app')

@section('page-title', 'My Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">📝</div>
                <div class="value">{{ $myRequests }}</div>
                <h3>Total Requests</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">✅</div>
                <div class="value">{{ $approvedByMe }}</div>
                <h3>Approved Requests</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div style="font-size: 24px;">📦</div>
                <div class="value">{{ $myEquipment }}</div>
                <h3>My Equipment</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Recent Activity</h5>
                </div>
                <div class="card-body">
                    @if($recentActivity->count())
                        <div class="timeline">
                            @foreach($recentActivity as $log)
                                <div class="timeline-item mb-3">
                                    <div style="font-weight: bold; color: #2c3e50;">{{ $log->action }}</div>
                                    <small class="text-muted">{{ $log->description }}</small>
                                    <div style="font-size: 12px; color: #95a5a6;">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <hr class="my-2">
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No recent activity</p>
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
                    <a href="{{ route('requests.create') }}" class="btn btn-success btn-sm w-100 mb-2">➕ Request Equipment</a>
                    <a href="{{ route('requests.index') }}" class="btn btn-info btn-sm w-100 mb-2">📝 My Requests</a>
                    <a href="{{ route('equipment.index') }}" class="btn btn-primary btn-sm w-100">📋 View Equipment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
