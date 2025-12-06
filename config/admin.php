<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin URL Prefix
    |--------------------------------------------------------------------------
    |
    | This is the URL prefix for accessing the admin panel.
    | Change this to something unique and unpredictable for security.
    | Example: bekabo-control-panel, secure-admin-2025, etc.
    |
    */
    'url_prefix' => env('ADMIN_URL_PREFIX', 'bekabo-control-panel'),

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist Restriction
    |--------------------------------------------------------------------------
    |
    | Enable IP-based access control for the admin panel.
    | Only IPs in the whitelist will be able to access admin routes.
    |
    */
    'ip_restriction_enabled' => env('ADMIN_IP_RESTRICTION', false),

    'ip_whitelist' => [
        '127.0.0.1',     // Localhost IPv4
        '::1',           // Localhost IPv6
        // Add your production IPs here
        // '203.0.113.0',   // Example office IP
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Protect against brute-force attacks by limiting login attempts.
    |
    */
    'login_throttle' => [
        'max_attempts' => env('ADMIN_MAX_LOGIN_ATTEMPTS', 10),
        'lockout_minutes' => env('ADMIN_LOCKOUT_MINUTES', 30),
        'track_by_ip' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Logging
    |--------------------------------------------------------------------------
    |
    | Log security events like failed logins and blocked IPs.
    |
    */
    'log_failed_logins' => true,
    'log_blocked_ips' => true,
];
