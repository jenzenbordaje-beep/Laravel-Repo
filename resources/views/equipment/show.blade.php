@extends('layouts.app')

@section('page-title', $equipment->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Equipment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Name:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $equipment->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Serial Number:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $equipment->serial_number }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $equipment->status === 'available' ? 'success' : 'info' }}">
                                {{ ucfirst($equipment->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Assigned To:</strong>
                        </div>
                        <div class="col-md-8">
                            @if($equipment->assignedUser)
                                {{ $equipment->assignedUser->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $equipment->description ?? 'No description' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Created:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $equipment->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('equipment.archive', $equipment) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Archive this equipment?')">Archive</button>
                            </form>
                            <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    @else
                        <a href="{{ route('equipment.index') }}" class="btn btn-secondary">Back</a>
                    @endif
                </div>
            </div>
        </div>

        @if($equipment->requests->count())
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Requests</h5>
                    </div>
                    <div class="card-body">
                        @foreach($equipment->requests as $req)
                            <div class="mb-2">
                                <strong>{{ $req->requester->name }}</strong>
                                <span class="badge bg-{{ $req->status === 'pending' ? 'warning' : ($req->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                                <small class="text-muted d-block">{{ $req->created_at->format('M d, Y') }}</small>
                            </div>
                            <hr class="my-2">
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
