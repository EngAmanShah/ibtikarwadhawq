<?php

namespace App\Support;

use Illuminate\Http\Request;

/** معالجة موحّدة لقيم الحقول القادمة من النماذج */
class Fields
{
    /** قواعد التحقق لمجموعة حقول */
    public static function rules(array $fields): array
    {
        $rules = [];
        foreach ($fields as $f) {
            $name = $f['name'] ?? $f['key'];
            $req = ! empty($f['required']) ? 'required' : 'nullable';
            $rules[$name] = match ($f['type'] ?? 'text') {
                'image' => ['nullable', ...explode('|', Uploader::IMAGE_RULE)],
                'file' => ['nullable', ...explode('|', Uploader::FILE_RULE)],
                'email' => [$req, 'email', 'max:191'],
                'url' => [$req, 'url', 'max:500'],
                'color' => [$req, 'regex:/^#[0-9a-fA-F]{6}$/'],
                'list' => ['nullable', 'array'],
                'toggle' => ['nullable', 'boolean'],
                'date' => [$req, 'date'],
                'code', 'textarea' => [$req, 'string', 'max:20000'],
                default => [$req, 'string', 'max:1000'],
            };
            if (($f['type'] ?? '') === 'list') $rules[$name . '.*'] = ['nullable', 'string', 'max:1000'];
            if (! empty($f['required']) && in_array($f['type'] ?? '', ['image', 'file'])) {
                // مطلوب فقط إذا لم يكن هناك ملف حالي — يُتحقق منه في الكنترولر
            }
        }
        return $rules;
    }

    /** أسماء الحقول بالعربي لرسائل الخطأ */
    public static function attributes(array $fields): array
    {
        $out = [];
        foreach ($fields as $f) $out[$f['name'] ?? $f['key']] = $f['label'];
        return $out;
    }

    /**
     * يعيد القيمة الجديدة للحقل. يعيد $current إذا لم يتغير (مثل صورة لم تُرفع).
     */
    public static function value(Request $request, array $f, $current, string $folder)
    {
        $name = $f['name'] ?? $f['key'];
        $type = $f['type'] ?? 'text';

        if (in_array($type, ['image', 'file'])) {
            if ($request->hasFile($name)) {
                $path = Uploader::store($request->file($name), $folder);
                Uploader::delete($current);
                return $path;
            }
            if ($request->boolean($name . '__remove')) {
                Uploader::delete($current);
                return null;
            }
            return $current;
        }

        if ($type === 'list') {
            if (! $request->has($name . '__list')) return $current;
            $items = array_values(array_filter(array_map('trim', (array) $request->input($name, [])), fn ($v) => $v !== ''));
            return $items;
        }

        if ($type === 'toggle') {
            return $request->boolean($name) ? '1' : '0';
        }

        $v = $request->input($name);
        return is_string($v) ? trim($v) : $v;
    }
}
