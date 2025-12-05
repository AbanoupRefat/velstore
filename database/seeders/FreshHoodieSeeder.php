<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FreshHoodieSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean up existing data (Orders & Products)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_details')->truncate();
        DB::table('orders')->truncate();
        DB::table('product_variant_attribute_values')->truncate();
        DB::table('product_variant_translations')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('product_images')->truncate();
        DB::table('product_translations')->truncate();
        DB::table('product_attribute_values')->truncate();
        DB::table('products')->truncate();
        DB::table('attribute_value_translations')->truncate();
        DB::table('attribute_values')->truncate();
        DB::table('attributes')->truncate();
        DB::table('brands')->truncate();
        DB::table('brand_translations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $languages = Language::where('active', 1)->get();

        // 2. Create Attributes (Size & Color)
        $sizeAttr = Attribute::create(['name' => 'Size']);
        $colorAttr = Attribute::create(['name' => 'Color']);

        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $colors = [
            'Black' => ['ar' => 'أسود', 'hex' => '#000000'],
            'Navy' => ['ar' => 'كحلي', 'hex' => '#000080'],
            'Grey' => ['ar' => 'رمادي', 'hex' => '#808080'],
            'White' => ['ar' => 'أبيض', 'hex' => '#FFFFFF'],
            'Burgundy' => ['ar' => 'عنابي', 'hex' => '#800020'], // New Color
        ];

        // Seed Sizes
        foreach ($sizes as $size) {
            $attrValue = AttributeValue::create([
                'attribute_id' => $sizeAttr->id,
                'value' => $size,
            ]);
            foreach ($languages as $lang) {
                $attrValue->translations()->create([
                    'language_code' => $lang->code,
                    'translated_value' => $size
                ]);
            }
        }

        // Seed Colors
        foreach ($colors as $colorName => $details) {
            $attrValue = AttributeValue::create([
                'attribute_id' => $colorAttr->id,
                'value' => $colorName, // Store name, hex can be handled in frontend mapping or separate column if exists
            ]);
            foreach ($languages as $lang) {
                $attrValue->translations()->create([
                    'language_code' => $lang->code,
                    'translated_value' => $lang->code === 'ar' ? $details['ar'] : $colorName
                ]);
            }
        }

        // 3. Create Brand "bekabo"
        $brand = Brand::create([
            'slug' => 'bekabo',
            'logo_url' => 'brands/bekabo-logo.png', // Placeholder
            'status' => 'active',
        ]);
        foreach ($languages as $lang) {
            $brand->translations()->create([
                'locale' => $lang->code,
                'name' => 'Bekabo',
                'description' => 'Premium Hoodies & Streetwear',
            ]);
        }

        // 4. Create Products (6 Hoodies)
        $vendor = Vendor::first() ?? Vendor::factory()->create();
        $category = Category::where('slug', 'hoodies')->first();
        
        // Fallback if category doesn't exist (though CategorySeeder should run first)
        if (!$category) {
             $category = Category::create(['slug' => 'hoodies', 'status' => 1]);
        }

        $products = [
            [
                'name_en' => 'Classic Black Hoodie',
                'name_ar' => 'هودي أسود كلاسيك',
                'slug' => 'classic-black-hoodie',
                'price' => 850,
                'description_en' => 'The essential black hoodie for your wardrobe. Soft, durable, and perfect fit.',
                'description_ar' => 'الهودي الأسود الأساسي لدولابك. ناعم، متين، ومقاس مثالي.',
                'colors' => ['Black'],
                'image_prompt' => 'A premium black pullover hoodie on white background, front view, product photography style',
            ],
            [
                'name_en' => 'Burgundy Zip-Up',
                'name_ar' => 'هودي عنابي بسوسته',
                'slug' => 'burgundy-zip-up',
                'price' => 950,
                'description_en' => 'Stylish burgundy zip-up hoodie. Easy to wear, premium fabric.',
                'description_ar' => 'هودي عنابي بسوسته شيك. سهل اللبس، خامة ممتازة.',
                'colors' => ['Burgundy'],
                'image_prompt' => 'A burgundy red zip-up hoodie on white background, front view, product photography style',
            ],
            [
                'name_en' => 'Oversized Grey Hoodie',
                'name_ar' => 'هودي رمادي واسع',
                'slug' => 'oversized-grey-hoodie',
                'price' => 1100,
                'description_en' => 'Trendy oversized fit in heather grey. Maximum comfort.',
                'description_ar' => 'قصة واسعة عصرية باللون الرمادي. راحة قصوى.',
                'colors' => ['Grey'],
                'image_prompt' => 'A grey oversized hoodie on white background, front view, product photography style',
            ],
            [
                'name_en' => 'Navy Premium Hoodie',
                'name_ar' => 'هودي كحلي بريميوم',
                'slug' => 'navy-premium-hoodie',
                'price' => 1200,
                'description_en' => 'High-end navy blue hoodie with heavyweight cotton.',
                'description_ar' => 'هودي كحلي عالي الجودة بقطن ثقيل.',
                'colors' => ['Navy'],
                'image_prompt' => 'A navy blue premium hoodie on white background, front view, product photography style',
            ],
            [
                'name_en' => 'White Athletic Hoodie',
                'name_ar' => 'هودي أبيض رياضي',
                'slug' => 'white-athletic-hoodie',
                'price' => 900,
                'description_en' => 'Clean white hoodie designed for movement and style.',
                'description_ar' => 'هودي أبيض نظيف مصمم للحركة والأناقة.',
                'colors' => ['White'],
                'image_prompt' => 'A white athletic hoodie on white background, front view, product photography style',
            ],
            [
                'name_en' => 'Bekabo Signature Hoodie',
                'name_ar' => 'هودي بيكابو المميز',
                'slug' => 'bekabo-signature-hoodie',
                'price' => 1500,
                'description_en' => 'Our signature design in burgundy and black. Limited edition.',
                'description_ar' => 'تصميمنا المميز باللون العنابي والأسود. إصدار محدود.',
                'colors' => ['Burgundy', 'Black'],
                'image_prompt' => 'A two-tone burgundy and black hoodie on white background, front view, product photography style',
            ],
        ];

        foreach ($products as $item) {
            $product = Product::create([
                'shop_id' => 1,
                'vendor_id' => $vendor->id,
                'slug' => $item['slug'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'product_type' => 'variable',
                'status' => 1,
            ]);

            // Translations
            foreach ($languages as $lang) {
                $product->translations()->create([
                    'language_code' => $lang->code,
                    'name' => $lang->code === 'ar' ? $item['name_ar'] : $item['name_en'],
                    'description' => $lang->code === 'ar' ? $item['description_ar'] : $item['description_en'],
                ]);
            }

            // Placeholder Image (Will be replaced by generated ones if available)
            // Using a generic placeholder for now
            $product->images()->create([
                'name' => $item['slug'] . '.png',
                'image_url' => 'products/' . $item['slug'] . '.png', // We will try to save generated images here
                'type' => 'thumb',
            ]);

            // Create Variants
            $sizesAttrValues = AttributeValue::where('attribute_id', $sizeAttr->id)->get();
            $colorsAttrValues = AttributeValue::where('attribute_id', $colorAttr->id)
                                              ->whereIn('value', $item['colors'])
                                              ->get();

            foreach ($sizesAttrValues as $size) {
                foreach ($colorsAttrValues as $color) {
                    // Pricing logic: XL/XXL cost more
                    $price = $item['price'];
                    if ($size->value === 'XL') $price += 50;
                    if ($size->value === 'XXL') $price += 100;

                    $variant = $product->variants()->create([
                        'variant_slug' => Str::slug("{$item['name_en']} {$size->value}-{$color->value}").'-'.uniqid(),
                        'price' => $price,
                        'discount_price' => $price,
                        'stock' => rand(10, 50),
                        'SKU' => strtoupper(substr($item['slug'], 0, 3).'-'.substr($size->value, 0, 1).'-'.substr($color->value, 0, 1).'-'.uniqid()),
                        'weight' => '0.8',
                        'dimensions' => '30x20x5 cm',
                        'is_primary' => ($size->value === 'M' && $color->value === $item['colors'][0]) ? 1 : 0,
                    ]);

                    // Variant Translations
                    foreach ($languages as $lang) {
                        $sizeName = $size->translations()->where('language_code', $lang->code)->first()->translated_value ?? $size->value;
                        $colorName = $color->translations()->where('language_code', $lang->code)->first()->translated_value ?? $color->value;
                        
                        $variant->translations()->create([
                            'language_code' => $lang->code,
                            'name' => "{$sizeName} - {$colorName}",
                        ]);
                    }

                    // Link Attributes
                    foreach ([$size->id, $color->id] as $attrValueId) {
                        DB::table('product_variant_attribute_values')->insert([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'attribute_value_id' => $attrValueId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        ProductAttributeValue::firstOrCreate([
                            'product_id' => $product->id,
                            'attribute_value_id' => $attrValueId,
                        ]);
                    }
                }
            }
        }
    }
}
