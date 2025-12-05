<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            AdminSeeder::class,
            SiteSettingsSeeder::class,
            CurrencySeeder::class,
            CategorySeeder::class,
            // BrandSeeder::class, // Replaced by FreshHoodieSeeder
            // AttributeSeeder::class, // Replaced by FreshHoodieSeeder
            // ProductSeeder::class, // Replaced by FreshHoodieSeeder
            FreshHoodieSeeder::class, // NEW
            MenuSeeder::class,
            BannerSeeder::class,
            ThemeSeeder::class,
            // OrderSeeder::class, // Cleared in FreshHoodieSeeder
            PaymentGatewaySeeder::class,
            PaymentSeeder::class,
            RefundSeeder::class,
            PageContentSeeder::class,
        ]);
    }
}
