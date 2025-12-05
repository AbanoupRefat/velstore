<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['name_en' => 'Cairo', 'name_ar' => 'القاهرة', 'shipping_fee' => 50, 'delivery_days' => 1],
            ['name_en' => 'Giza', 'name_ar' => 'الجيزة', 'shipping_fee' => 50, 'delivery_days' => 1],
            ['name_en' => 'Alexandria', 'name_ar' => 'الإسكندرية', 'shipping_fee' => 60, 'delivery_days' => 2],
            ['name_en' => 'Qalyubia', 'name_ar' => 'القليوبية', 'shipping_fee' => 45, 'delivery_days' => 1],
            ['name_en' => 'Port Said', 'name_ar' => 'بورسعيد', 'shipping_fee' => 70, 'delivery_days' => 2],
            ['name_en' => 'Suez', 'name_ar' => 'السويس', 'shipping_fee' => 65, 'delivery_days' => 2],
            ['name_en' => 'Dakahlia', 'name_ar' => 'الدقهلية', 'shipping_fee' => 60, 'delivery_days' => 2],
            ['name_en' => 'Sharqia', 'name_ar' => 'الشرقية', 'shipping_fee' => 55, 'delivery_days' => 2],
            ['name_en' => 'Gharbia', 'name_ar' => 'الغربية', 'shipping_fee' => 60, 'delivery_days' => 2],
            ['name_en' => 'Monufia', 'name_ar' => 'المنوفية', 'shipping_fee' => 55, 'delivery_days' => 2],
            ['name_en' => 'Beheira', 'name_ar' => 'البحيرة', 'shipping_fee' => 65, 'delivery_days' => 2],
            ['name_en' => 'Ismailia', 'name_ar' => 'الإسماعيلية', 'shipping_fee' => 65, 'delivery_days' => 2],
            ['name_en' => 'Kafr El Sheikh', 'name_ar' => 'كفر الشيخ', 'shipping_fee' => 65, 'delivery_days' => 2],
            ['name_en' => 'Damietta', 'name_ar' => 'دمياط', 'shipping_fee' => 70, 'delivery_days' => 3],
            ['name_en' => 'Aswan', 'name_ar' => 'أسوان', 'shipping_fee' => 100, 'delivery_days' => 4],
            ['name_en' => 'Asyut', 'name_ar' => 'أسيوط', 'shipping_fee' => 80, 'delivery_days' => 3],
            ['name_en' => 'Beni Suef', 'name_ar' => 'بني سويف', 'shipping_fee' => 65, 'delivery_days' => 2],
            ['name_en' => 'Faiyum', 'name_ar' => 'الفيوم', 'shipping_fee' => 60, 'delivery_days' => 2],
            ['name_en' => 'Luxor', 'name_ar' => 'الأقصر', 'shipping_fee' => 95, 'delivery_days' => 4],
            ['name_en' => 'Minya', 'name_ar' => 'المنيا', 'shipping_fee' => 75, 'delivery_days' => 3],
            ['name_en' => 'Qena', 'name_ar' => 'قنا', 'shipping_fee' => 90, 'delivery_days' => 3],
            ['name_en' => 'Sohag', 'name_ar' => 'سوهاج', 'shipping_fee' => 85, 'delivery_days' => 3],
            ['name_en' => 'Red Sea', 'name_ar' => 'البحر الأحمر', 'shipping_fee' => 120, 'delivery_days' => 4],
            ['name_en' => 'New Valley', 'name_ar' => 'الوادي الجديد', 'shipping_fee' => 150, 'delivery_days' => 5],
            ['name_en' => 'Matrouh', 'name_ar' => 'مرسى مطروح', 'shipping_fee' => 110, 'delivery_days' => 4],
            ['name_en' => 'North Sinai', 'name_ar' => 'شمال سيناء', 'shipping_fee' => 130, 'delivery_days' => 5],
            ['name_en' => 'South Sinai', 'name_ar' => 'جنوب سيناء', 'shipping_fee' => 140, 'delivery_days' => 5],
        ];

        foreach ($governorates as $gov) {
            Governorate::create(array_merge($gov, ['active' => true]));
        }
    }
}
