<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Hanya admin yang boleh akses (middleware cek di route)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }
}
