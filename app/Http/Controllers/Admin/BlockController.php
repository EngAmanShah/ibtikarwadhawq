<?php

namespace App\Http\Controllers\Admin;

use App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlockController extends CrudController
{
    protected string $model = Models\Block::class;
    protected string $base = 'admin.blocks';
    protected string $type = 'blocks';
    protected string $title = '';
    protected string $singular = '';
    protected string $icon = '';
    protected string $layout = 'rows';
    protected ?string $subField = 'text';
    protected array $cfg = [];

    public function __construct()
    {
        $group = request()->route('group');
        $this->cfg = config("cms.blocks.$group") ?? abort(404);
        $this->title = $this->cfg['title'];
        $this->singular = $this->cfg['singular'];
        $this->icon = $this->cfg['icon'];
        $this->preview = $this->cfg['preview'];
        $this->description = 'اسحب العناصر لتغيير ترتيبها، واستخدم المفتاح لإظهارها أو إخفائها.';
        if (! in_array('text', $this->cfg['fields'])) $this->subField = in_array('label', $this->cfg['fields']) ? 'label' : null;
    }

    protected function params(): array { return ['group' => request()->route('group')]; }
    protected function query() { return parent::query()->where('group', request()->route('group')); }
    protected function extra(Request $request, ?Model $item = null): array { return ['group' => request()->route('group')]; }

    protected function fields(?Model $item = null): array
    {
        $labels = ($this->cfg['labels'] ?? []) + ['icon' => 'الأيقونة', 'label' => 'العنوان الصغير', 'title' => 'العنوان', 'text' => 'النص'];
        $out = [];
        foreach ($this->cfg['fields'] as $k) {
            $out[] = ['key' => $k, 'label' => $labels[$k], 'type' => $k === 'icon' ? 'icon' : ($k === 'text' ? 'textarea' : 'text'), 'required' => $k === 'title' && ! isset($this->cfg['labels'])];
        }
        $out[] = ['key' => 'is_active', 'label' => 'ظاهر في الموقع', 'type' => 'toggle'];
        return $out;
    }
}
