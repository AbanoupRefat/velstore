<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestrictAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if IP restriction is enabled
        if (!config('admin.ip_restriction_enabled', false)) {
            return $next($request);
        }

        $clientIp = $request->ip();
        $whitelist = config('admin.ip_whitelist', []);

        // Allow if IP is in whitelist
        if (in_array($clientIp, $whitelist)) {
            return $next($request);
        }

        // Log blocked access attempt
        if (config('admin.log_blocked_ips', true)) {
            Log::warning('Admin access blocked', [
                'ip' => $clientIp,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        // Return 403 Forbidden
        abort(403, 'Access denied. Your IP address is not authorized to access this area.');
    }
}
