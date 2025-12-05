<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->upsert([
            [
                'name' => 'Egyptian Pound',
                'code' => 'EGP',
                'symbol' => 'LE',
                'exchange_rate' => 1.0000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['code'], ['symbol', 'name', 'exchange_rate', 'updated_at']);
    }
}
