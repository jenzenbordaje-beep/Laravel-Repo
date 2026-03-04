<?php

namespace App\Http\Controllers;

use App\Models\Request as EquipmentRequest;
use App\Models\Equipment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isEmployee()) {
            // Employees see their own requests
            $requests = EquipmentRequest::where('requested_by', $user->id)
                ->with('equipment', 'requester', 'approver')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Admins see all requests
            $requests = EquipmentRequest::with('equipment', 'requester', 'approver')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('requests.index', ['requests' => $requests]);
    }

    public function create()
    {
        if (!Auth::user()->isEmployee()) {
            abort(403);
        }

        $equipment = Equipment::where('status', 'available')
            ->where('is_archived', false)
            ->get();

        return view('requests.create', ['equipment' => $equipment]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isEmployee()) {
            abort(403);
        }

        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'reason' => 'required|string|max:500',
        ]);

        $equipmentRequest = EquipmentRequest::create([
            'requested_by' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'request',
            'entity_type' => 'request',
            'entity_id' => $equipmentRequest->id,
            'description' => "Requested equipment: {$equipmentRequest->equipment->name}",
        ]);

        return redirect()->route('requests.index')->with('success', 'Equipment request submitted successfully');
    }

    public function show(EquipmentRequest $request)
    {
        // Check authorization
        if (Auth::user()->isEmployee() && $request->requested_by !== Auth::id()) {
            abort(403);
        }

        return view('requests.show', ['request' => $request]);
    }

    public function approve(EquipmentRequest $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Update equipment status
        $request->equipment->update([
            'status' => 'in_use',
            'assigned_to' => $request->requested_by,
            'assigned_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'approve',
            'entity_type' => 'request',
            'entity_id' => $request->id,
            'description' => "Approved request from {$request->requester->name} for {$request->equipment->name}",
        ]);

        return redirect()->route('requests.index')->with('success', 'Request approved successfully');
    }

    public function reject(Request $request, EquipmentRequest $equipmentRequest)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'approval_note' => 'required|string|max:500',
        ]);

        $equipmentRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approval_note' => $validated['approval_note'],
            'approved_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'reject',
            'entity_type' => 'request',
            'entity_id' => $equipmentRequest->id,
            'description' => "Rejected request from {$equipmentRequest->requester->name} for {$equipmentRequest->equipment->name}",
        ]);

        return redirect()->route('requests.index')->with('success', 'Request rejected successfully');
    }
}
