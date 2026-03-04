<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            // Super admin sees all activity logs
            $logs = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(30);
        } else {
            // Others see their own activity logs
            $logs = $user->activityLogs()
                ->orderBy('created_at', 'desc')
                ->paginate(30);
        }

        return view('activity-logs.index', ['logs' => $logs]);
    }

    public function show(ActivityLog $log)
    {
        // Check authorization
        if (!Auth::user()->isSuperAdmin() && $log->user_id !== Auth::id()) {
            abort(403);
        }

        return view('activity-logs.show', ['log' => $log]);
    }
}
