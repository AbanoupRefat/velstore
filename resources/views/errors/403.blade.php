@extends('errors::minimal')

@section('title', 'Access Denied')
@section('code', '403')
@section('message')
    <div style="text-align: center; padding: 40px 20px;">
        <div style="font-size: 80px; margin-bottom: 20px;">🚫</div>
        <h1 style="font-size: 36px; margin-bottom: 15px; color: #2d3748;">Access Denied</h1>
        <p style="font-size: 18px; color: #718096; margin-bottom: 25px;">
            Your IP address is not authorized to access this area.
        </p>
        <div style="background: #f7fafc; border-left: 4px solid #fc8181; padding: 20px; border-radius: 8px; max-width: 500px; margin: 0 auto; text-align: left;">
            <strong style="color: #c53030;">Security Notice:</strong><br>
            This admin panel is protected by IP whitelist. Only authorized IP addresses can access this area.
            If you believe this is an error, please contact the system administrator.
        </div>
    </div>
@endsection
