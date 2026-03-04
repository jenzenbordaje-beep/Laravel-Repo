@extends('layouts.app')

@section('page-title', 'Request Equipment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Request Equipment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('requests.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="equipment_id" class="form-label">Equipment *</label>
                            <select class="form-control @error('equipment_id') is-invalid @enderror" 
                                    id="equipment_id" name="equipment_id" required>
                                <option value="">Select Equipment</option>
                                @foreach($equipment as $item)
                                    <option value="{{ $item->id }}" @if(old('equipment_id') === (string)$item->id) selected @endif>
                                        {{ $item->name }} ({{ $item->serial_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('equipment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Request *</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="5" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Provide details about why you need this equipment</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
