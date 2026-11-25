<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Document;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Database\Seeder;

/**
 * يملأ قاعدة البيانات بكل محتوى الموقع الحالي كما هو تمامًا.
 */
class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->services();
        $this->slides();
        $this->clients();
        $this->documents();
        $this->branches();
        $this->blocks();
        Setting::flush();
    }

    protected function settings(): void
    {
        foreach (config('cms.pages') as $page) {
            foreach ($page['sections'] as $section) {
                if (! empty($section['toggle'])) Setting::put($section['toggle'], '1');
                foreach ($section['fields'] as $f) {
                    Setting::put($f['key'], $f['default'] ?? null);
                }
            }
        }
    }

    protected function services(): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/data/services.json'), true);

        $cards = [
            'finishing' => ['خدمات التشطيبات', 'لياسة، سيراميك، دهانات وعزل', 'fa-trowel-bricks', 'assets/images/services/finishing.webp', 'تنفيذ تشطيبات متكاملة تشمل اللياسة والسيراميك والدهانات والجبس بورد والسباكة والكهرباء بدقة عالية.'],
            'decoration' => ['خدمات التصميم والديكور', 'تصميم داخلي وتنفيذ ديكور', 'fa-wand-magic-sparkles', 'assets/images/service-details/interior-design-01.webp', 'تصميم وتنفيذ ديكورات داخلية تجمع بين الذوق العملي وتناسق الخامات والإضاءة وتوزيع العناصر.'],
            'woodworks' => ['خدمات الأخشاب والأبواب', 'أبواب، خزائن وأعمال خشبية', 'fa-door-open', 'assets/images/service-details/wpc-pvc-doors-works.webp', 'توريد وتنفيذ أعمال خشبية وأبواب داخلية بمقاسات دقيقة وتشطيبات مناسبة لطبيعة كل مساحة.'],
            'landscape' => ['اعمال الاندسكيب', 'تنسيق حدائق وشلالات وري', 'fa-seedling', 'assets/images/service-details/landscape-design-and-execution.webp', 'تصميم وتنفيذ الحدائق والشلالات وشبكات الري والممرات والجلسات الخارجية بجودة عملية ومظهر متناسق.'],
        ];

        $sort = 1;
        foreach ($cards as $slug => [$title, $subtitle, $icon, $card, $excerpt]) {
            $d = $data[$slug] ?? [];
            $service = Service::create([
                'slug' => $slug, 'title' => $title, 'subtitle' => $subtitle, 'icon' => $icon,
                'excerpt' => $excerpt, 'card_image' => $card,
                'hero_image' => $d['hero_image'] ?? $card,
                'hero_title' => $d['hero_title'] ?? $title,
                'hero_text' => $d['hero_text'] ?? '',
                'cta_text' => $d['cta_text'] ?? '',
                'meta_title' => $d['meta_title'] ?? $title,
                'meta_description' => $d['meta_description'] ?? $excerpt,
                'sort' => $sort++,
            ]);

            $i = 1;
            foreach ($d['items'] ?? [] as $item) {
                ServiceItem::create([
                    'service_id' => $service->id, 'title' => $item['title'], 'icon' => $item['icon'],
                    'image' => $item['image'], 'points' => $item['points'], 'sort' => $i++,
                ]);
            }
        }
    }

    protected function slides(): void
    {
        $items = [
            ['أعمال اللياسة', 'assets/images/service-details/plastering-works-square.webp'],
            ['أعمال السيراميك', 'assets/images/home/ceramic-works.webp'],
            ['أعمال الجبس بورد', 'assets/images/service-details/gypsum-board-works.webp'],
            ['أعمال السباكة والكهرباء', 'assets/images/home/plumbing-electrical-works.webp'],
            ['أعمال العزل', 'assets/images/home/insulation-works.webp'],
            ['المظلات والسواتر', 'assets/images/service-details/shades-and-fences-works.webp'],
            ['الحديد والألمنيوم', 'assets/images/service-details/metal-and-aluminum-works.webp'],
            ['الدهانات بجميع أنواعها', 'assets/images/service-details/painting-works-all-types.webp'],
        ];
        foreach ($items as $i => [$title, $image]) {
            Slide::create(['title' => $title, 'image' => $image, 'sort' => $i + 1]);
        }
    }

    protected function clients(): void
    {
        $items = ['الشركة السعودية للكهرباء', 'وزارة الداخلية', 'وزارة الشؤون البلدية والقروية والإسكان', 'شركة المياه الوطنية', 'أرامكو السعودية', 'جلفانكو', 'شركة الفتح الإعمارية FCC'];
        foreach ($items as $i => $name) {
            Client::create(['name' => $name, 'logo' => 'assets/images/clients/logo-' . ($i + 1) . '.png', 'sort' => $i + 1]);
        }
    }

    protected function documents(): void
    {
        $items = [
            ['شهادة السجل التجاري', 'commercial-registration'],
            ['شهادة الالتزام', 'compliance-certificate'],
            ['شهادة التوطين', 'nationalization-certificate'],
            ['شهادة ضريبة القيمة المضافة', 'vat-certificate'],
            ['شهادة المديونيات', 'debt-certificate'],
            ['شهادة السلامة والصحة المهنية', 'osh-certificate'],
        ];
        foreach ($items as $i => [$title, $file]) {
            Document::create([
                'title' => $title,
                'preview_image' => "assets/images/documents/$file.webp",
                'file' => "assets/docs/$file.pdf",
                'sort' => $i + 1,
            ]);
        }
    }

    protected function branches(): void
    {
        Branch::create([
            'title' => 'فرع الجبيل', 'icon' => 'fa-building', 'phone' => '+966535193153', 'whatsapp' => '966535193153',
            'address' => 'الجبيل — المملكة العربية السعودية 35514', 'map_title' => 'فرع الجبيل على الخريطة',
            'map_embed' => 'https://www.google.com/maps?q=%D8%A7%D8%A8%D8%AA%D9%83%D8%A7%D8%B1%20%D9%88%D8%B0%D9%88%D9%82%20%D9%84%D9%84%D9%85%D9%82%D8%A7%D9%88%D9%84%D8%A7%D8%AA%20%D8%A7%D9%84%D8%AC%D8%A8%D9%8A%D9%84&output=embed',
            'sort' => 1,
        ]);
        Branch::create([
            'title' => 'فرع الأحساء', 'icon' => 'fa-location-dot', 'phone' => '+966508334037', 'whatsapp' => '966508334037',
            'address' => 'الأحساء — المملكة العربية السعودية', 'map_title' => 'فرع الأحساء على الخريطة',
            'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1297158.297144021!2d51.46178002310677!3d25.216575401614904!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e3791510ba96975%3A0xddcb0ac344408293!2z2KfYqNiq2YPYp9ixINmI2LDZiNmCINmE2YTYr9mK2YPZiNix2KfYqg!5e1!3m2!1sar!2seg!4v1783768249866!5m2!1sar!2seg',
            'sort' => 2,
        ]);
        Branch::create([
            'title' => 'إدارة المشاريع', 'icon' => 'fa-helmet-safety', 'phone' => '+966537309033', 'whatsapp' => '966537309033',
            'address' => 'إدارة المشاريع', 'sort' => 3,
        ]);
    }

    protected function blocks(): void
    {
        $groups = [
            'about_feature' => [
                ['icon' => 'fa-ruler-combined', 'title' => 'رفع المقاسات بدقة', 'text' => 'نرفع أبعاد المساحة ونحدد النقاط الحرجة قبل طلب أي خامة أو بدء التنفيذ.'],
                ['icon' => 'fa-layer-group', 'title' => 'تناسق الخامات', 'text' => 'نربط بين الأرضيات والجدران والأعمال الخشبية والأبواب حتى تظهر كصورة واحدة.'],
                ['icon' => 'fa-hammer', 'title' => 'جودة التنفيذ', 'text' => 'نراقب الحواف والزوايا والتقفيلات لأنها أول ما يظهر عند الاستخدام اليومي.'],
                ['icon' => 'fa-clipboard-check', 'title' => 'فحص قبل التسليم', 'text' => 'نجري جولة مراجعة شاملة للتأكد من اكتمال كل التفاصيل قبل اعتبار العمل جاهزًا.'],
            ],
            'about_card' => [
                ['icon' => 'fa-eye', 'label' => 'رؤيتنا', 'title' => 'أن نكون من الشركات المميزة في مجال التشطيب الداخلي', 'text' => 'نسعى لنكون من الخيارات الموثوقة في تنفيذ وتشطيب المساحات، من خلال الجمع بين جودة التنفيذ وجمال التصميم وسهولة الاستخدام.'],
                ['icon' => 'fa-bullseye', 'label' => 'رسالتنا', 'title' => 'حلول عملية بخامات مناسبة وتفاصيل نهائية متقنة', 'text' => 'نقدم أعمال التشطيب والديكور والأعمال الخشبية وفق أسلوب منظم يراعي احتياج العميل وطبيعة المساحة وراحة الاستخدام.'],
                ['icon' => 'fa-route', 'label' => 'مراحل العمل', 'title' => 'من المعاينة الميدانية إلى تقفيلة آخر زاوية', 'text' => 'نبدأ بزيارة الموقع وفهم المساحة، ثم نحدد المعالجة والخامات، ونرفع المقاسات، وننفذ بترتيب واضح، ونراجع كل تفصيلة قبل التسليم.'],
            ],
            'about_value' => [
                ['icon' => 'fa-crosshairs', 'title' => 'الدقة في التفاصيل'],
                ['icon' => 'fa-list-check', 'title' => 'وضوح مراحل العمل'],
                ['icon' => 'fa-handshake', 'title' => 'احترام ذوق العميل'],
                ['icon' => 'fa-gem', 'title' => 'اختيار خامات مناسبة'],
                ['icon' => 'fa-award', 'title' => 'جودة التقفيل والتشطيب'],
            ],
            'company_fact' => [
                ['icon' => 'fa-building', 'label' => 'الاسم', 'title' => 'ابتكار وذوق للمقاولات'],
                ['icon' => 'fa-location-dot', 'label' => 'الموقع', 'title' => 'الجبيل، المملكة'],
                ['icon' => 'fa-fingerprint', 'label' => 'الرقم الوطني الموحد', 'title' => '7037409559'],
                ['icon' => 'fa-tag', 'label' => 'نوع الكيان', 'title' => ''],
                ['icon' => 'fa-circle-check', 'label' => 'حالة السجل', 'title' => 'نشط'],
            ],
        ];

        foreach ($groups as $group => $rows) {
            foreach ($rows as $i => $row) {
                Block::create($row + ['group' => $group, 'sort' => $i + 1]);
            }
        }
    }
}
