<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceItemController extends CrudController
{
    protected string $model = Models\ServiceItem::class;
    protected string $base = 'admin.services.items';
    protected string $type = 'items';
    protected string $title = 'أقسام الخدمة';
    protected string $singular = 'قسم';
    protected string $icon = 'fa-list-check';
    protected string $folder = 'services';
    protected ?string $imageField = 'image';

    protected function fields(?Model $item = null): array
    {
        return [
            ['key' => 'title', 'label' => 'عنوان القسم', 'type' => 'text', 'required' => true, 'half' => true],
            ['key' => 'icon', 'label' => 'الأيقونة', 'type' => 'icon', 'half' => true],
            ['key' => 'points', 'label' => 'النقاط', 'type' => 'list', 'add_label' => 'إضافة نقطة', 'help' => 'كل سطر يظهر كنقطة. اضغط Enter لإضافة سطر جديد.'],
            ['key' => 'image', 'label' => 'صورة القسم', 'type' => 'image', 'required' => true],
            ['key' => 'is_active', 'label' => 'ظاهر في الموقع', 'type' => 'toggle'],
        ];
    }

    protected function itemMeta(Models\Service $service): array
    {
        $m = $this->meta();
        $m['index'] = route('admin.services.edit', $service) . '#items';
        $m['store'] = route('admin.services.items.store', $service);
        $m['update'] = fn ($i) => route('admin.services.items.update', $i);
        $m['destroy'] = fn ($i) => route('admin.services.items.destroy', $i);
        $m['parent'] = $service;
        $m['preview'] = '/services/' . $service->slug;
        return $m;
    }

    protected function meta(): array
    {
        return [
            'title' => $this->title, 'singular' => $this->singular, 'icon' => $this->icon, 'description' => '', 'layout' => 'grid',
            'image' => 'image', 'titleField' => 'title', 'sub' => null, 'preview' => '/', 'contain' => false, 'type' => 'items',
        ];
    }

    public function create(...$args)
    {
        $service = Models\Service::findOrFail(end($args));
        return view('admin.crud.form', ['meta' => $this->itemMeta($service), 'item' => null, 'fields' => $this->fields()]);
    }

    public function edit(...$args)
    {
        $item = Models\ServiceItem::findOrFail(end($args));
        return view('admin.crud.form', ['meta' => $this->itemMeta($item->service), 'item' => $item, 'fields' => $this->fields($item)]);
    }

    public function store(Request $request, ...$args)
    {
        $service = Models\Service::findOrFail(end($args));
        $item = new Models\ServiceItem(['service_id' => $service->id]);
        $this->save($request, $item);
        Models\Activity::log('إضافة', 'قسم في ' . $service->title . ': ' . $item->title, 'fa-plus');
        return redirect(route('admin.services.edit', $service) . '#items')->with('ok', 'تمت إضافة القسم');
    }

    public function destroy(...$args)
    {
        $item = Models\ServiceItem::findOrFail(end($args));
        $service = $item->service;
        \App\Support\Uploader::delete($item->image);
        $item->delete();
        return redirect(route('admin.services.edit', $service) . '#items')->with('ok', 'تم حذف القسم');
    }
}
