<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $description = null)
    {
        ActivityLog::create([
            'user_id'     => Auth::check() ? Auth::id() : null,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}
