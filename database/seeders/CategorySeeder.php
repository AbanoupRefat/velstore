<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $languages = Language::where('active', 1)->pluck('code')->toArray();

            $categories = [
                [
                    'slug' => 'hoodies',
                    'parent_slug' => null,
                    'translations' => [
                        'en' => ['name' => 'Hoodies', 'description' => 'Premium Quality Hoodies'],
                        'ar' => ['name' => 'هوديز', 'description' => 'هوديز عالية الجودة'],
                    ],
                    'image' => 'https://i.postimg.cc/mgTTQWtW/07-300x300-1-1-removebg-preview.png',
                ],
                [
                    'slug' => 'men-hoodies',
                    'parent_slug' => 'hoodies',
                    'translations' => [
                        'en' => ['name' => 'Men', 'description' => 'Hoodies for Men'],
                        'ar' => ['name' => 'رجالي', 'description' => 'هوديز رجالي'],
                    ],
                    'image' => 'https://i.postimg.cc/QM9NMkFF/cat7-removebg-preview.png',
                ],
                [
                    'slug' => 'women-hoodies',
                    'parent_slug' => 'hoodies',
                    'translations' => [
                        'en' => ['name' => 'Women', 'description' => 'Hoodies for Women'],
                        'ar' => ['name' => 'حريمي', 'description' => 'هوديز حريمي'],
                    ],
                    'image' => 'https://i.postimg.cc/ZKwJFD39/cat1-removebg-preview.png',
                ],
                [
                    'slug' => 'unisex-hoodies',
                    'parent_slug' => 'hoodies',
                    'translations' => [
                        'en' => ['name' => 'Unisex', 'description' => 'For Everyone'],
                        'ar' => ['name' => 'للجنسين', 'description' => 'للجميع'],
                    ],
                    'image' => 'https://i.postimg.cc/VkSP9smT/cat2-removebg-preview.png',
                ],
                [
                    'slug' => 'zip-up-hoodies',
                    'parent_slug' => 'hoodies',
                    'translations' => [
                        'en' => ['name' => 'Zip-Up', 'description' => 'Zip-Up Style Hoodies'],
                        'ar' => ['name' => 'بسوسته', 'description' => 'هوديز بسوسته'],
                    ],
                    'image' => 'https://i.postimg.cc/VkSP9smT/cat2-removebg-preview.png',
                ],
            ];

            foreach ($categories as $categoryData) {
                $parentId = $categoryData['parent_slug']
                    ? Category::where('slug', $categoryData['parent_slug'])->value('id')
                    : null;

                $category = Category::firstOrCreate(
                    ['slug' => $categoryData['slug']],
                    [
                        'parent_category_id' => $parentId,
                        'status' => true,
                    ]
                );

                $imageUrl = $categoryData['image'];
                $imageName = basename($imageUrl);

                try {
                    $imageContents = file_get_contents($imageUrl);
                    $localPath = 'categories/'.$imageName;
                    Storage::disk('public')->put($localPath, $imageContents);
                } catch (\Exception $e) {
                    $localPath = $imageUrl;
                }

                foreach ($languages as $lang) {
                    $translation = $categoryData['translations'][$lang] ?? $categoryData['translations']['en'];

                    $category->translations()->updateOrCreate(
                        ['language_code' => $lang],
                        [
                            'name' => $translation['name'],
                            'description' => $translation['description'],
                            'image_url' => $localPath,
                        ]
                    );
                }
            }
        });
    }
}
