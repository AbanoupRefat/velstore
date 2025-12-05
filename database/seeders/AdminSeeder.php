<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@velstore.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // Change this in production!
                'phone' => '1234567890',
            ]
        );

        // Create a default vendor first
        \App\Models\Vendor::firstOrCreate(
            ['email' => 'vendor@velstore.com'],
            [
                'id' => 1,
                'name' => 'Demo Vendor',
                'status' => 1,
                'password' => '$2y$12$sD1fufZKo.z25XNEdh3wJu8moMXvIypyagJzAEtJ/JgJoRx0TdeIS', // password
            ]
        );

        // Create a default shop for the products
        \App\Models\Shop::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Velstore Demo Shop',
                'vendor_id' => 1,
                'status' => 'active',
                'slug' => 'velstore-demo-shop',
            ]
        );
    }
}
