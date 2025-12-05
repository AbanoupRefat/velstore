<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::where('active', 1)->get();

        $pages = [
            [
                'slug' => 'about',
                'status' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'About Us',
                        'content' => '<h2>About Our Hoodies Store</h2>
                        <p>Welcome to our premium hoodies collection! We specialize in high-quality, comfortable hoodies that combine style with functionality.</p>
                        <h3>Our Story</h3>
                        <p>Founded with a passion for quality streetwear, we\'ve been providing customers with the best hoodies since [Year]. Our mission is to offer comfortable, stylish hoodies that you\'ll love to wear every day.</p>
                        <h3>Why Choose Us?</h3>
                        <ul>
                            <li>Premium quality materials</li>
                            <li>Unique designs</li>
                            <li>Affordable prices</li>
                            <li>Fast delivery across Egypt</li>
                            <li>Excellent customer service</li>
                        </ul>',
                    ],
                    'ar' => [
                        'title' => 'من نحن',
                        'content' => '<h2>عن متجر الهوديز الخاص بنا</h2>
                        <p>مرحباً بكم في مجموعتنا المميزة من الهوديز! نحن متخصصون في الهوديز عالية الجودة والمريحة التي تجمع بين الأناقة والعملية.</p>
                        <h3>قصتنا</h3>
                        <p>تأسسنا بشغف لملابس الشارع عالية الجودة، ونقدم لعملائنا أفضل الهوديز منذ [السنة]. مهمتنا هي تقديم هوديز مريحة وأنيقة ستحب ارتداءها كل يوم.</p>
                        <h3>لماذا تختارنا؟</h3>
                        <ul>
                            <li>مواد عالية الجودة</li>
                            <li>تصاميم فريدة</li>
                            <li>أسعار معقولة</li>
                            <li>توصيل سريع في جميع أنحاء مصر</li>
                            <li>خدمة عملاء ممتازة</li>
                        </ul>',
                    ],
                ],
            ],
            [
                'slug' => 'services',
                'status' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Our Services',
                        'content' => '<h2>What We Offer</h2>
                        <h3>Free Shipping</h3>
                        <p>Enjoy free shipping on orders over 500 EGP across Egypt.</p>
                        <h3>Cash on Delivery</h3>
                        <p>Pay when you receive your order. We accept cash on delivery for your convenience.</p>
                        <h3>Easy Returns</h3>
                        <p>Not satisfied? Return your hoodie within 14 days for a full refund.</p>
                        <h3>Size Guide</h3>
                        <p>Check our detailed size guide to find your perfect fit.</p>
                        <h3>Custom Orders</h3>
                        <p>Need a custom design or bulk order? Contact us for special requests.</p>',
                    ],
                    'ar' => [
                        'title' => 'خدماتنا',
                        'content' => '<h2>ما نقدمه</h2>
                        <h3>شحن مجاني</h3>
                        <p>استمتع بالشحن المجاني على الطلبات التي تزيد عن 500 جنيه مصري في جميع أنحاء مصر.</p>
                        <h3>الدفع عند الاستلام</h3>
                        <p>ادفع عند استلام طلبك. نقبل الدفع عند الاستلام لراحتك.</p>
                        <h3>إرجاع سهل</h3>
                        <p>غير راضٍ؟ قم بإرجاع الهودي الخاص بك خلال 14 يومًا لاسترداد كامل المبلغ.</p>
                        <h3>دليل المقاسات</h3>
                        <p>تحقق من دليل المقاسات التفصيلي للعثور على المقاس المثالي لك.</p>
                        <h3>طلبات مخصصة</h3>
                        <p>هل تحتاج إلى تصميم مخصص أو طلب بالجملة؟ اتصل بنا للطلبات الخاصة.</p>',
                    ],
                ],
            ],
            [
                'slug' => 'blog',
                'status' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Blog',
                        'content' => '<h2>Latest News & Style Tips</h2>
                        <p>Coming soon! Check back for hoodie styling tips, fashion trends, and news about our latest collections.</p>
                        <h3>Stay Tuned For:</h3>
                        <ul>
                            <li>How to style your hoodie for different occasions</li>
                            <li>Care tips to keep your hoodie looking new</li>
                            <li>Behind the scenes of our design process</li>
                            <li>Customer stories and reviews</li>
                        </ul>',
                    ],
                    'ar' => [
                        'title' => 'المدونة',
                        'content' => '<h2>آخر الأخبار ونصائح الموضة</h2>
                        <p>قريباً! تابعنا للحصول على نصائح تنسيق الهوديز، واتجاهات الموضة، وأخبار عن أحدث مجموعاتنا.</p>
                        <h3>ترقبوا:</h3>
                        <ul>
                            <li>كيفية تنسيق الهودي لمناسبات مختلفة</li>
                            <li>نصائح العناية للحفاظ على الهودي الخاص بك كالجديد</li>
                            <li>من وراء الكواليس لعملية التصميم</li>
                            <li>قصص العملاء والمراجعات</li>
                        </ul>',
                    ],
                ],
            ],
            [
                'slug' => 'contact',
                'status' => 1,
                'translations' => [
                    'en' => [
                        'title' => 'Contact Us',
                        'content' => '<h2>Get In Touch</h2>
                        <p>Have questions? We\'d love to hear from you!</p>
                        <h3>Contact Information</h3>
                        <p><strong>Email:</strong> info@yourstore.com</p>
                        <p><strong>Phone:</strong> +20 XXX XXX XXXX</p>
                        <p><strong>WhatsApp:</strong> +20 XXX XXX XXXX</p>
                        <h3>Business Hours</h3>
                        <p>Saturday - Thursday: 10:00 AM - 8:00 PM</p>
                        <p>Friday: Closed</p>
                        <h3>Location</h3>
                        <p>Cairo, Egypt</p>
                        <p><em>Note: We operate online only. Visit our store from the comfort of your home!</em></p>',
                    ],
                    'ar' => [
                        'title' => 'اتصل بنا',
                        'content' => '<h2>تواصل معنا</h2>
                        <p>هل لديك أسئلة؟ نحن نحب أن نسمع منك!</p>
                        <h3>معلومات الاتصال</h3>
                        <p><strong>البريد الإلكتروني:</strong> info@yourstore.com</p>
                        <p><strong>الهاتف:</strong> +20 XXX XXX XXXX</p>
                        <p><strong>واتساب:</strong> +20 XXX XXX XXXX</p>
                        <h3>ساعات العمل</h3>
                        <p>السبت - الخميس: 10:00 صباحاً - 8:00 مساءً</p>
                        <p>الجمعة: مغلق</p>
                        <h3>الموقع</h3>
                        <p>القاهرة، مصر</p>
                        <p><em>ملاحظة: نعمل عبر الإنترنت فقط. قم بزيارة متجرنا من راحة منزلك!</em></p>',
                    ],
                ],
            ],
        ];

        foreach ($pages as $pageData) {
            $page = Page::firstOrCreate(
                ['slug' => $pageData['slug']],
                ['status' => $pageData['status']]
            );

            foreach ($languages as $lang) {
                if (isset($pageData['translations'][$lang->code])) {
                    PageTranslation::updateOrCreate(
                        [
                            'page_id' => $page->id,
                            'language_code' => $lang->code,
                        ],
                        $pageData['translations'][$lang->code]
                    );
                }
            }
        }
    }
}
