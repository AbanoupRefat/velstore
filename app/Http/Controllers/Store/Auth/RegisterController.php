<?php

namespace App\Http\Controllers\Store\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('themes.xylo.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers',
            'password' => 'required|string|confirmed|min:8',
        ]);
        
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the customer in immediately
        Auth::guard('customer')->login($customer);

        // Send welcome email asynchronously (won't block the response)
        Mail::to($customer->email)->queue(new WelcomeEmail($customer));

        return redirect()->route('xylo.home')->with('success', __('Welcome to Bekabo! Check your email for a special welcome message.'));
    }
}
