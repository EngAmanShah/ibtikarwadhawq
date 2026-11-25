<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends CrudController
{
    protected string $model = Models\Branch::class;
    protected string $base = 'admin.branches';
    protected string $type = 'branches';
    protected string $title = 'الفروع والأرقام';
    protected string $singular = 'فرع';
    protected string $icon = 'fa-map-location-dot';
    protected string $description = 'تظهر في صفحة التواصل، أسفل الموقع، وزر الواتساب العائم.';
    protected string $layout = 'rows';
    protected ?string $subField = 'address';
    protected ?string $preview = '/contact';

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'title', 'label' => 'اسم الفرع', 'type' => 'text', 'required' => true, 'half' => true],
            ['key' => 'icon', 'label' => 'الأيقونة', 'type' => 'icon', 'half' => true],
            ['key' => 'phone', 'label' => 'رقم الهاتف', 'type' => 'phone', 'half' => true, 'help' => 'مثال: +966535193153'],
            ['key' => 'whatsapp', 'label' => 'رقم الواتساب', 'type' => 'phone', 'half' => true, 'help' => 'اتركه فارغًا لاستخدام رقم الهاتف نفسه.'],
            ['key' => 'address', 'label' => 'العنوان', 'type' => 'text'],
            ['key' => 'map_title', 'label' => 'عنوان الخريطة', 'type' => 'text'],
            ['key' => 'map_embed', 'label' => 'الخريطة', 'type' => 'textarea', 'rows' => 2, 'help' => 'من خرائط جوجل: مشاركة ← تضمين خريطة ← انسخ الكود والصقه هنا. أو اكتب اسم المكان فقط. اتركه فارغًا لإخفاء الخريطة.'],
            ['key' => 'is_active', 'label' => 'ظاهر في الموقع', 'type' => 'toggle'],
        ];
    }
}
