<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends CrudController
{
    protected string $model = Models\Document::class;
    protected string $base = 'admin.documents';
    protected string $type = 'documents';
    protected string $title = 'الوثائق والشهادات';
    protected string $singular = 'وثيقة';
    protected string $icon = 'fa-file-pdf';
    protected string $description = 'الشهادات الرسمية في صفحة الوثائق. أضف تاريخ الانتهاء ليتم تنبيهك قبلها.';
    protected string $folder = 'documents';
    protected ?string $imageField = 'preview_image';
    protected ?string $preview = '/documents';
    protected bool $contain = true;

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'title', 'label' => 'اسم الوثيقة', 'type' => 'text', 'required' => true],
            ['key' => 'file', 'label' => 'ملف الوثيقة (PDF)', 'type' => 'file', 'required' => true, 'half' => true],
            ['key' => 'preview_image', 'label' => 'صورة المعاينة', 'type' => 'image', 'half' => true, 'help' => 'صورة للصفحة الأولى من الوثيقة.'],
            ['key' => 'expires_at', 'label' => 'تاريخ انتهاء الوثيقة (اختياري)', 'type' => 'date', 'help' => 'سيظهر لك تنبيه في لوحة التحكم قبل الانتهاء بـ 30 يومًا.'],
            ['key' => 'is_active', 'label' => 'ظاهرة في الموقع', 'type' => 'toggle'],
        ];
    }
}
