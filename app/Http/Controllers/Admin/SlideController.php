<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SlideController extends CrudController
{
    protected string $model = Models\Slide::class;
    protected string $base = 'admin.slides';
    protected string $type = 'slides';
    protected string $title = 'شريط خدمات التشطيب';
    protected string $singular = 'صورة';
    protected string $icon = 'fa-film';
    protected string $description = 'الصور المتحركة في الصفحة الرئيسية. اسحب البطاقات لتغيير ترتيبها.';
    protected string $folder = 'slides';
    protected ?string $imageField = 'image';

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'title', 'label' => 'اسم الخدمة (يظهر تحت الصورة)', 'type' => 'text', 'required' => true],
            ['key' => 'image', 'label' => 'الصورة', 'type' => 'image', 'required' => true, 'shape' => 'square cover', 'help' => 'يفضّل صورة مربعة.'],
            ['key' => 'is_active', 'label' => 'ظاهر في الموقع', 'type' => 'toggle'],
        ];
    }
}
