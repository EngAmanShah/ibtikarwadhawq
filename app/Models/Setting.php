<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $memo = null;

    /** كل الإعدادات كمصفوفة key => value (مخزّنة مؤقتًا) */
    public static function allCached(): array
    {
        if (static::$memo !== null) return static::$memo;

        try {
            return static::$memo = Cache::rememberForever('cms.settings', function () {
                return static::query()->pluck('value', 'key')->all();
            });
        } catch (\Throwable $e) {
            return static::$memo = [];
        }
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allCached();
        if (array_key_exists($key, $all) && $all[$key] !== null) {
            return $all[$key];
        }
        return $default ?? static::schemaDefault($key);
    }

    public static function list(string $key): array
    {
        $v = static::get($key);
        if (is_array($v)) return $v;
        $decoded = json_decode((string) $v, true);
        return is_array($decoded) ? array_values(array_filter($decoded, fn ($x) => $x !== '' && $x !== null)) : [];
    }

    public static function enabled(string $key): bool
    {
        $v = static::get($key, '1');
        return $v === true || $v === '1' || $v === 1;
    }

    public static function put(string $key, $value): void
    {
        if (is_array($value)) $value = json_encode(array_values($value), JSON_UNESCAPED_UNICODE);
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function flush(): void
    {
        static::$memo = null;
        Cache::forget('cms.settings');
    }

    /** القيمة الافتراضية من config/cms.php */
    public static function schemaDefault(string $key)
    {
        static $map = null;
        if ($map === null) {
            $map = [];
            foreach (config('cms.pages', []) as $page) {
                foreach ($page['sections'] as $section) {
                    if (! empty($section['toggle'])) $map[$section['toggle']] = '1';
                    foreach ($section['fields'] as $f) {
                        $map[$f['key']] = is_array($f['default'] ?? null)
                            ? json_encode($f['default'], JSON_UNESCAPED_UNICODE)
                            : ($f['default'] ?? null);
                    }
                }
            }
        }
        return $map[$key] ?? null;
    }
}
