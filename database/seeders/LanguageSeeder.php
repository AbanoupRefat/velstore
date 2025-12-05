<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'translated_text' => 'English', 'active' => true],
            ['code' => 'ar', 'name' => 'Arabic', 'translated_text' => 'العربية', 'active' => true],
        ];

        foreach ($languages as $lang) {
            Language::updateOrCreate(['code' => $lang['code']], [
                'name' => $lang['name'],
                'active' => $lang['active'],
            ]);
        }
    }
}
