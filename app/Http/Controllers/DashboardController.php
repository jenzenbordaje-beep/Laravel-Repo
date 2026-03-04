<?php

namespace App\Http\Controllers;

use App\Models\Request as EquipmentRequest;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics based on user role
        if ($user->isSuperAdmin()) {
            $pendingRequests = EquipmentRequest::where('status', 'pending')->count();
            $approvedRequests = EquipmentRequest::where('status', 'approved')->count();
            $rejectedRequests = EquipmentRequest::where('status', 'rejected')->count();
            $totalEquipment = Equipment::count();
            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            
            return view('dashboard.super-admin', [
                'pendingRequests' => $pendingRequests,
                'approvedRequests' => $approvedRequests,
                'rejectedRequests' => $rejectedRequests,
                'totalEquipment' => $totalEquipment,
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
            ]);
        } elseif ($user->isAdmin()) {
            $pendingRequests = EquipmentRequest::where('status', 'pending')->count();
            $approvedRequests = EquipmentRequest::where('status', 'approved')->count();
            $totalEquipment = Equipment::count();
            $availableEquipment = Equipment::where('status', 'available')->count();
            $recentRequests = EquipmentRequest::orderBy('created_at', 'desc')->limit(5)->get();
            
            return view('dashboard.admin', [
                'pendingRequests' => $pendingRequests,
                'approvedRequests' => $approvedRequests,
                'totalEquipment' => $totalEquipment,
                'availableEquipment' => $availableEquipment,
                'recentRequests' => $recentRequests,
            ]);
        } else {
            // Employee dashboard
            $myRequests = EquipmentRequest::where('requested_by', $user->id)->count();
            $approvedByMe = EquipmentRequest::where('requested_by', $user->id)
                ->where('status', 'approved')->count();
            $myEquipment = $user->equipment()->count();
            $recentActivity = $user->activityLogs()->orderBy('created_at', 'desc')->limit(5)->get();
            
            return view('dashboard.employee', [
                'myRequests' => $myRequests,
                'approvedByMe' => $approvedByMe,
                'myEquipment' => $myEquipment,
                'recentActivity' => $recentActivity,
            ]);
        }
    }
}
