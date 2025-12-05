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

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $languages = Language::where('active', 1)->get();

            $sizeAttr = Attribute::firstOrCreate(['name' => 'Size']);
            $colorAttr = Attribute::firstOrCreate(['name' => 'Color']);

            $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
            $colors = ['Black', 'Grey', 'Navy', 'White', 'Beige'];

            // Create Attributes
            foreach ($sizes as $size) {
                $attrValue = AttributeValue::firstOrCreate([
                    'attribute_id' => $sizeAttr->id,
                    'value' => $size,
                ]);
                
                foreach ($languages as $lang) {
                    $attrValue->translations()->firstOrCreate(
                        ['language_code' => $lang->code],
                        ['translated_value' => $size]
                    );
                }
            }

            foreach ($colors as $color) {
                $attrValue = AttributeValue::firstOrCreate([
                    'attribute_id' => $colorAttr->id,
                    'value' => $color,
                ]);

                foreach ($languages as $lang) {
                    $translatedColor = match($lang->code) {
                        'ar' => match($color) {
                            'Black' => 'أسود',
                            'Grey' => 'رمادي',
                            'Navy' => 'كحلي',
                            'White' => 'أبيض',
                            'Beige' => 'بيج',
                            default => $color
                        },
                        default => $color
                    };

                    $attrValue->translations()->firstOrCreate(
                        ['language_code' => $lang->code],
                        ['translated_value' => $translatedColor]
                    );
                }
            }

            $vendor = Vendor::first() ?? Vendor::factory()->create();
            $category = Category::where('slug', 'hoodies')->first() ?? Category::factory()->create();
            $brand = Brand::first() ?? Brand::factory()->create(['name' => 'Velstore']);

            $hoodies = [
                [
                    'name_en' => 'Essential Black Hoodie',
                    'name_ar' => 'هودي أسود أساسي',
                    'slug' => 'essential-black-hoodie',
                    'image' => 'https://i.postimg.cc/zBCkRRvb/T-Shirt-removebg-preview.png', // Placeholder, user can update
                    'description_en' => 'Premium cotton hoodie, perfect for everyday wear.',
                    'description_ar' => 'هودي قطن عالي الجودة، مثالي للاستخدام اليومي.',
                    'price' => 850,
                ],
                [
                    'name_en' => 'Oversized Grey Hoodie',
                    'name_ar' => 'هودي رمادي واسع',
                    'slug' => 'oversized-grey-hoodie',
                    'image' => 'https://i.postimg.cc/YS1FXBHT/images-removebg-preview.png',
                    'description_en' => 'Comfortable oversized fit with soft fleece lining.',
                    'description_ar' => 'قصة واسعة مريحة مع بطانة صوف ناعمة.',
                    'price' => 950,
                ],
                [
                    'name_en' => 'Navy Blue Zip-Up',
                    'name_ar' => 'هودي كحلي بسوسته',
                    'slug' => 'navy-blue-zip-up',
                    'image' => 'https://i.postimg.cc/2Sn3YdKZ/images-1-removebg-preview-2.png',
                    'description_en' => 'Classic zip-up hoodie with durable metal zipper.',
                    'description_ar' => 'هودي كلاسيك بسوسته معدنية متينة.',
                    'price' => 1100,
                ],
                [
                    'name_en' => 'Beige Pullover',
                    'name_ar' => 'بلوفر بيج',
                    'slug' => 'beige-pullover',
                    'image' => 'https://i.postimg.cc/WpDkKZTM/images-2-removebg-preview-1.png',
                    'description_en' => 'Stylish beige pullover, matches with everything.',
                    'description_ar' => 'بلوفر بيج أنيق، يليق مع كل حاجة.',
                    'price' => 900,
                ],
            ];

            foreach ($hoodies as $item) {
                $product = Product::create([
                    'shop_id' => 1,
                    'vendor_id' => $vendor->id,
                    'slug' => $item['slug'],
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'product_type' => 'variable',
                    'status' => 1,
                ]);

                // Product Translations
                foreach ($languages as $lang) {
                    $product->translations()->create([
                        'language_code' => $lang->code,
                        'name' => $lang->code === 'ar' ? $item['name_ar'] : $item['name_en'],
                        'description' => $lang->code === 'ar' ? $item['description_ar'] : $item['description_en'],
                    ]);
                }

                // Product Image
                $imageUrl = $item['image'];
                $imageName = basename($imageUrl);
                try {
                    $imageContents = file_get_contents($imageUrl);
                    $localPath = 'products/'.$imageName;
                    Storage::disk('public')->put($localPath, $imageContents);
                } catch (\Exception $e) {
                    $localPath = $imageUrl;
                }

                $product->images()->create([
                    'name' => $imageName,
                    'image_url' => $localPath,
                    'type' => 'thumb',
                ]);

                // Create Variants
                $sizesAttrValues = AttributeValue::where('attribute_id', $sizeAttr->id)->get();
                $colorsAttrValues = AttributeValue::where('attribute_id', $colorAttr->id)->get();

                foreach ($sizesAttrValues as $size) {
                    foreach ($colorsAttrValues as $color) {
                        $price = $item['price'];
                        // Add slight price variation for larger sizes if needed, but keeping simple for now
                        
                        $variant = $product->variants()->create([
                            'variant_slug' => Str::slug("{$item['name_en']} {$size->value}-{$color->value}").'-'.uniqid(),
                            'price' => $price,
                            'discount_price' => $price, // No discount initially
                            'stock' => rand(10, 50),
                            'SKU' => strtoupper(substr($item['slug'], 0, 3).'-'.substr($size->value, 0, 1).'-'.substr($color->value, 0, 1).'-'.uniqid()),
                            'barcode' => null,
                            'weight' => '0.8',
                            'dimensions' => '30x20x5 cm',
                            'is_primary' => ($size->value === 'M' && $color->value === 'Black') ? 1 : 0,
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
        });

    }
}
