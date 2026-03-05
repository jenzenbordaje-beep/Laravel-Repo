<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $query = Equipment::query();

        // handle optional archived filter (admins/superadmins only)
        if (request()->query('archived')) {
            if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
                abort(403);
            }
            $query->where('is_archived', true);
        } else {
            // default behaviour: hide archived items
            $query->where('is_archived', false);

            // regular employees only see available equipment
            if (Auth::user()->isEmployee()) {
                $query->where('status', 'available');
            }
        }

        $equipment = $query->paginate(15);

        return view('equipment.index', ['equipment' => $equipment]);
    }

    public function create()
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }
        
        return view('equipment.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:equipment',
            'description' => 'nullable|string',
        ]);

        $equipment = Equipment::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'entity_type' => 'equipment',
            'entity_id' => $equipment->id,
            'description' => "Created equipment: {$equipment->name}",
        ]);

        return redirect()->route('equipment.index')->with('success', 'Equipment created successfully');
    }

    public function show(Equipment $equipment)
    {
        return view('equipment.show', ['equipment' => $equipment]);
    }

    public function edit(Equipment $equipment)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        return view('equipment.edit', ['equipment' => $equipment]);
    }

    public function update(Request $request, Equipment $equipment)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:available,in_use',
        ]);

        $equipment->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'entity_type' => 'equipment',
            'entity_id' => $equipment->id,
            'description' => "Updated equipment: {$equipment->name}",
        ]);

        return redirect()->route('equipment.show', $equipment)->with('success', 'Equipment updated successfully');
    }

    public function archive(Equipment $equipment)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $equipment->update([
            'is_archived' => true,
            'archived_at' => now(),
            'status' => 'archived',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'archive',
            'entity_type' => 'equipment',
            'entity_id' => $equipment->id,
            'description' => "Archived equipment: {$equipment->name}",
        ]);

        return redirect()->route('equipment.index')->with('success', 'Equipment archived successfully');
    }

    public function restore(Equipment $equipment)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        $equipment->update([
            'is_archived' => false,
            'archived_at' => null,
            'status' => 'available',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'restore',
            'entity_type' => 'equipment',
            'entity_id' => $equipment->id,
            'description' => "Restored equipment: {$equipment->name}",
        ]);

        return redirect()->route('equipment.index', ['archived' => 1])->with('success', 'Equipment restored successfully');
    }
}
