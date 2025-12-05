<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        // ---- PayPal (Disabled) ----
        $paypalId = DB::table('payment_gateways')->insertGetId([
            'name' => 'PayPal',
            'code' => 'paypal',
            'description' => 'PayPal payment gateway',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('payment_gateway_configs')->insert([
            [
                'gateway_id' => $paypalId,
                'key_name' => 'client_id',
                'key_value' => 'your-paypal-client-id',
                'is_encrypted' => true,
                'environment' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_id' => $paypalId,
                'key_name' => 'client_secret',
                'key_value' => 'your-paypal-client-secret',
                'is_encrypted' => true,
                'environment' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ---- Stripe (Disabled) ----
        $stripeId = DB::table('payment_gateways')->insertGetId([
            'name' => 'Stripe',
            'code' => 'stripe',
            'description' => 'Stripe payment gateway',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('payment_gateway_configs')->insert([
            [
                'gateway_id' => $stripeId,
                'key_name' => 'public_key',
                'key_value' => 'your-stripe-public-key',
                'is_encrypted' => false,
                'environment' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'gateway_id' => $stripeId,
                'key_name' => 'secret_key',
                'key_value' => 'your-stripe-secret-key',
                'is_encrypted' => true,
                'environment' => 'sandbox',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ---- InstaPay (New) ----
        $instapayId = DB::table('payment_gateways')->insertGetId([
            'name' => 'InstaPay',
            'code' => 'instapay',
            'description' => 'Manual payment via InstaPay',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // No config needed for manual payment, but we can add instructions key if we want to make it dynamic later
        DB::table('payment_gateway_configs')->insert([
            [
                'gateway_id' => $instapayId,
                'key_name' => 'instructions',
                'key_value' => 'Send to 01xxxxxxxxx',
                'is_encrypted' => false,
                'environment' => 'production',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
