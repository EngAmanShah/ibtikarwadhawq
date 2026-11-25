<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Setting;
use App\Support\Fields;
use Illuminate\Http\Request;

/** محرر صفحات المحتوى والإعدادات — يُبنى تلقائيًا من config/cms.php */
class PageController extends Controller
{
    protected function page(string $key): array
    {
        $page = config("cms.pages.$key");
        abort_unless($page, 404);
        return $page + ['key' => $key];
    }

    public function edit(string $key)
    {
        $page = $this->page($key);
        $values = [];
        foreach ($page['sections'] as $section) {
            if (! empty($section['toggle'])) $values[$section['toggle']] = Setting::get($section['toggle'], '1');
            foreach ($section['fields'] as $f) {
                $v = Setting::get($f['key']);
                $values[$f['key']] = ($f['type'] ?? '') === 'list' ? Setting::list($f['key']) : $v;
            }
        }

        return view('admin.pages.edit', compact('page', 'values'));
    }

    public function update(Request $request, string $key)
    {
        $page = $this->page($key);
        $fields = collect($page['sections'])->flatMap(fn ($s) => $s['fields'])->all();

        $request->validate(Fields::rules($fields), [], Fields::attributes($fields));

        foreach ($page['sections'] as $section) {
            if (! empty($section['toggle'])) {
                Setting::put($section['toggle'], $request->boolean($section['toggle']) ? '1' : '0');
            }
        }

        foreach ($fields as $f) {
            $current = Setting::get($f['key']);
            $new = Fields::value($request, $f, $current, 'pages');
            if (($f['type'] ?? '') === 'color' && ! $new) $new = $f['default'] ?? null;
            Setting::put($f['key'], $new);
        }

        Setting::flush();
        Activity::log('تحديث', $page['title'], $page['icon']);

        return back()->with('ok', 'تم حفظ التعديلات ونشرها على الموقع مباشرة');
    }
}
