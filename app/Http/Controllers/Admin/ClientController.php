<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends CrudController
{
    protected string $model = Models\Client::class;
    protected string $base = 'admin.clients';
    protected string $type = 'clients';
    protected string $title = 'العملاء';
    protected string $singular = 'عميل';
    protected string $icon = 'fa-handshake';
    protected string $description = 'شعارات العملاء في قسم «عملاؤنا» بالصفحة الرئيسية.';
    protected string $folder = 'clients';
    protected ?string $imageField = 'logo';
    protected string $titleField = 'name';
    protected bool $contain = true;

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'name', 'label' => 'اسم العميل / الجهة', 'type' => 'text', 'required' => true],
            ['key' => 'logo', 'label' => 'الشعار', 'type' => 'image', 'required' => true, 'help' => 'يفضّل PNG بخلفية شفافة.'],
            ['key' => 'url', 'label' => 'رابط موقع العميل (اختياري)', 'type' => 'url'],
            ['key' => 'is_active', 'label' => 'ظاهر في الموقع', 'type' => 'toggle'],
        ];
    }
}
