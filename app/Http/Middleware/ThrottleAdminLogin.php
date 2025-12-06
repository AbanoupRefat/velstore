<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThrottleAdminLogin
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
        $ip = $request->ip();
        $key = 'admin_login_attempts_' . $ip;
        $lockoutKey = 'admin_login_lockout_' . $ip;

        // Check if IP is locked out
        if (Cache::has($lockoutKey)) {
            $remainingMinutes = Cache::get($lockoutKey);
            
            return response()->view('admin.auth.locked', [
                'remainingMinutes' => $remainingMinutes,
            ], 429);
        }

        // Get current attempts
        $attempts = Cache::get($key, 0);
        $maxAttempts = config('admin.login_throttle.max_attempts', 10);

        // Check if max attempts reached
        if ($attempts >= $maxAttempts) {
            $lockoutMinutes = config('admin.login_throttle.lockout_minutes', 30);
            
            // Set lockout
            Cache::put($lockoutKey, $lockoutMinutes, now()->addMinutes($lockoutMinutes));
            Cache::forget($key);

            // Log the lockout
            if (config('admin.log_failed_logins', true)) {
                Log::warning('Admin login locked out', [
                    'ip' => $ip,
                    'attempts' => $attempts,
                    'lockout_minutes' => $lockoutMinutes,
                    'timestamp' => now(),
                ]);
            }

            return response()->view('admin.auth.locked', [
                'remainingMinutes' => $lockoutMinutes,
            ], 429);
        }

        return $next($request);
    }

    /**
     * Increment failed login attempts
     */
    public static function incrementAttempts(Request $request)
    {
        $ip = $request->ip();
        $key = 'admin_login_attempts_' . $ip;
        $attempts = Cache::get($key, 0) + 1;
        
        // Store for 1 hour
        Cache::put($key, $attempts, now()->addHour());

        // Log failed attempt
        if (config('admin.log_failed_logins', true)) {
            Log::info('Admin login failed', [
                'ip' => $ip,
                'email' => $request->input('email'),
                'attempts' => $attempts,
                'timestamp' => now(),
            ]);
        }

        return $attempts;
    }

    /**
     * Clear login attempts on successful login
     */
    public static function clearAttempts(Request $request)
    {
        $ip = $request->ip();
        Cache::forget('admin_login_attempts_' . $ip);
        Cache::forget('admin_login_lockout_' . $ip);
    }

    /**
     * Get remaining attempts
     */
    public static function getRemainingAttempts(Request $request)
    {
        $ip = $request->ip();
        $key = 'admin_login_attempts_' . $ip;
        $attempts = Cache::get($key, 0);
        $maxAttempts = config('admin.login_throttle.max_attempts', 10);
        
        return max(0, $maxAttempts - $attempts);
    }
}
