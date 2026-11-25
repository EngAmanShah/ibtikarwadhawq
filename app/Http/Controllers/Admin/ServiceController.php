<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends CrudController
{
    protected string $model = Models\Service::class;
    protected string $base = 'admin.services';
    protected string $type = 'services';
    protected string $title = 'الخدمات';
    protected string $singular = 'خدمة';
    protected string $icon = 'fa-layer-group';
    protected string $description = 'الخدمات الرئيسية: تظهر في القائمة العلوية والصفحة الرئيسية ولكل خدمة صفحة تفاصيل خاصة.';
    protected string $folder = 'services';
    protected ?string $imageField = 'card_image';
    protected ?string $subField = 'subtitle';
    protected ?string $preview = '/services';

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'title', 'label' => 'اسم الخدمة', 'type' => 'text', 'required' => true, 'half' => true],
            ['key' => 'subtitle', 'label' => 'وصف قصير (يظهر في القائمة العلوية)', 'type' => 'text', 'half' => true],
            ['key' => 'icon', 'label' => 'الأيقونة', 'type' => 'icon', 'half' => true],
            ['key' => 'slug', 'label' => 'رابط الصفحة (بالإنجليزية)', 'type' => 'text', 'ltr' => true, 'half' => true, 'help' => 'مثال: finishing ← يصبح الرابط /services/finishing. اتركه فارغًا ليتولد تلقائيًا.'],
            ['key' => 'excerpt', 'label' => 'وصف بطاقة الخدمة', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'card_image', 'label' => 'صورة البطاقة', 'type' => 'image', 'required' => true, 'half' => true],
            ['key' => 'hero_image', 'label' => 'صورة غلاف صفحة الخدمة', 'type' => 'image', 'half' => true],
            ['key' => 'hero_title', 'label' => 'عنوان صفحة الخدمة', 'type' => 'text'],
            ['key' => 'hero_text', 'label' => 'النص التعريفي في صفحة الخدمة', 'type' => 'textarea'],
            ['key' => 'cta_text', 'label' => 'نص الشريط السفلي (دعوة للتواصل)', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'meta_title', 'label' => 'عنوان جوجل', 'type' => 'text', 'counter' => 60],
            ['key' => 'meta_description', 'label' => 'وصف جوجل', 'type' => 'textarea', 'rows' => 2, 'counter' => 160],
            ['key' => 'in_menu', 'label' => 'تظهر في القائمة العلوية والفوتر', 'type' => 'toggle', 'half' => true],
            ['key' => 'is_active', 'label' => 'الخدمة منشورة', 'type' => 'toggle', 'half' => true],
        ];
    }

    protected function extraRules(Model $item): array
    {
        return ['slug' => ['nullable', 'regex:/^[a-z0-9-]+$/', 'max:120', 'unique:services,slug,' . ($item->id ?? 'NULL')]];
    }

    protected function extra(Request $request, ?Model $item = null): array
    {
        $slug = $request->input('slug') ?: Str::slug($request->input('title')) ?: 'service-' . Str::lower(Str::random(5));
        while (Models\Service::where('slug', $slug)->where('id', '!=', $item->id ?? 0)->exists()) $slug .= '-2';
        return ['slug' => $slug];
    }

    public function store(Request $request)
    {
        $item = new Models\Service;
        $this->save($request, $item);
        Models\Activity::log('إضافة', 'خدمة: ' . $item->title, 'fa-plus');
        return redirect()->route('admin.services.edit', $item)->with('ok', 'تمت إضافة الخدمة — أضف الآن أقسام تفاصيلها بالأسفل');
    }
}
