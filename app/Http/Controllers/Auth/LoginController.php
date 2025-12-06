<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ThrottleAdminLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        return '/' . config('admin.url_prefix', 'bekabo-control-panel') . '/dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Clear login attempts on successful login
        ThrottleAdminLogin::clearAttempts($request);
    }

    /**
     * The user has failed to authenticate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Increment failed attempts
        $attempts = ThrottleAdminLogin::incrementAttempts($request);
        $remaining = ThrottleAdminLogin::getRemainingAttempts($request);

        // Add remaining attempts to session for display
        if ($remaining <= 3) {
            $request->session()->flash('remaining_attempts', $remaining);
        }

        return parent::sendFailedLoginResponse($request);
    }
}
