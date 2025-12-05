<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Insert orders and capture IDs
        $order1Id = DB::table('orders')->insertGetId([
            'customer_id' => null,
            'guest_email' => 'guest1@example.com',
            'total_price' => 300,
            'shipping_cost' => 50,
            'shipping_address' => '123 Test St, Cairo, Egypt',
            'billing_address' => '123 Test St, Cairo, Egypt',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order2Id = DB::table('orders')->insertGetId([
            'customer_id' => null,
            'guest_email' => 'guest2@example.com',
            'total_price' => 150,
            'shipping_cost' => 50,
            'shipping_address' => '456 Sample Rd, Giza, Egypt',
            'billing_address' => '456 Sample Rd, Giza, Egypt',
            'payment_method' => 'cash_on_delivery',
            'payment_status' => 'pending',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert order details linked to the real IDs
        DB::table('order_details')->insert([
            [
                'order_id' => $order1Id,
                'product_id' => 1,
                'variant_id' => null,
                'quantity' => 2,
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order1Id,
                'product_id' => 2,
                'variant_id' => null,
                'quantity' => 1,
                'price' => 50, // Adjusted to match total 300 (2*100 + 50 + 50 shipping)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'order_id' => $order2Id,
                'product_id' => 1,
                'variant_id' => null,
                'quantity' => 1,
                'price' => 100, // Adjusted to match total 150 (1*100 + 50 shipping)
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
