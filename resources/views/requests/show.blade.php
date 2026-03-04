@extends('layouts.app')

@section('page-title', 'Request Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Employee:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $request->requester->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Equipment:</strong>
                        </div>
                        <div class="col-md-8">
                            <a href="{{ route('equipment.show', $request->equipment) }}">
                                {{ $request->equipment->name }}
                            </a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Reason:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $request->reason }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    </div>

                    @if($request->isApproved() || $request->isRejected())
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Reviewed By:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $request->approver->name }}
                            </div>
                        </div>

                        @if($request->approval_note)
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Note:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ $request->approval_note }}
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Reviewed At:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $request->approved_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Requested:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $request->created_at->format('M d, Y h:i A') }}
                        </div>
                    </div>

                    <hr>

                    @if((Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) && $request->isPending())
                        <div class="d-flex gap-2">
                            <form action="{{ route('requests.approve', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this request?')">✅ Approve</button>
                            </form>

                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">❌ Reject</button>
                        </div>
                    @else
                        <a href="{{ route('requests.index') }}" class="btn btn-secondary">Back</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @if((Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) && $request->isPending())
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('requests.reject', $request) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="approval_note" class="form-label">Reason for Rejection *</label>
                                <textarea class="form-control" id="approval_note" name="approval_note" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
