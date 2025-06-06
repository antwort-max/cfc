<?php 

namespace App\Helpers;

use App\Models\WebActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(Request $request, string $eventType, ?array $data = null, ?int $duration = null): void
    {
        WebActivity::create([
            'user_id' => Auth::guard('customer')->id() ?? Auth::id(),
            'session_id' => $request->session()->getId(),
            'event_type' => $eventType,
            'event_data' => $data,
            'duration_seconds' => $duration,
            'url' => $request->fullUrl(),
            'referrer' => $request->headers->get('referer'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
