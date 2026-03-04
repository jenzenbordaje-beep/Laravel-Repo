@extends('layouts.app')

@section('page-title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Activity Logs</h5>
        </div>
        <div class="card-body">
            @if($logs->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        @if(Auth::user()->isSuperAdmin())
                                            <strong>{{ $log->user->name }}</strong>
                                        @else
                                            <strong>You</strong>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($log->action) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ ucfirst($log->entity_type) }}</small>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td>
                                        <small class="text-muted">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $logs->links('pagination::bootstrap-4') }}
            @else
                <div class="alert alert-info">No activity logs found</div>
            @endif
        </div>
    </div>
</div>
@endsection
