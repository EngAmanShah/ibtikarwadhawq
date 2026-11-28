<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('media')) {
    /** رابط عام لأي ملف مخزّن (assets/... أو uploads/... أو رابط خارجي) */
    function media(?string $path, string $fallback = ''): string
    {
        $path = trim((string) $path);
        if ($path === '') return $fallback ? asset($fallback) : '';
        if (preg_match('#^(https?:)?//#i', $path)) return $path;
        return asset(ltrim($path, '/'));
    }
}

if (! function_exists('is_public_host')) {
    /** هل النطاق نطاق إنتاج حقيقي (وليس localhost أو نطاق استضافة مؤقتة)؟ */
    function is_public_host(?string $host): bool
    {
        $host = strtolower(trim((string) $host));
        if ($host === '' || filter_var($host, FILTER_VALIDATE_IP)) return false;
        if (! preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/', $host)) return false;

        $blocked = [
            'localhost', 'local', 'test', 'example', 'invalid',
            'hostingersite.com', 'hostinger.site', 'ngrok.io', 'ngrok-free.app', 'ngrok.app',
            'loca.lt', 'trycloudflare.com', 'vercel.app', 'netlify.app', 'onrender.com',
        ];
        foreach ($blocked as $suffix) {
            if ($host === $suffix || str_ends_with($host, '.' . $suffix)) return false;
        }

        return true;
    }
}

if (! function_exists('site_base')) {
    /**
     * الرابط الأساسي المعتمد في Canonical و Sitemap وبيانات schema.org.
     * يتجاهل أي قيمة مخزّنة تشير إلى نطاق تطوير/استضافة مؤقتة (كاسماء المعاينة)
     * حتى لا تُوجّه محركات البحث إلى نطاق غير حقيقي.
     */
    function site_base(): string
    {
        static $base = null;
        if ($base !== null) return $base;

        foreach ([setting('site_url'), config('app.url'), url('/')] as $candidate) {
            $host = (string) parse_url(trim((string) $candidate), PHP_URL_HOST);
            if (is_public_host($host)) {
                return $base = 'https://' . preg_replace('/^www\./i', '', strtolower($host));
            }
        }

        return $base = rtrim(url('/'), '/');
    }
}

if (! function_exists('absolute_media')) {
    /** رابط مطلق لملف داخلي مبني على النطاق المعتمد (لبيانات schema.org والمشاركة) */
    function absolute_media(?string $path, string $fallback = ''): string
    {
        $path = trim((string) $path) ?: trim($fallback);
        if ($path === '') return '';
        if (preg_match('#^(https?:)?//#i', $path)) {
            return str_starts_with($path, '//') ? 'https:' . $path : $path;
        }
        return site_base() . '/' . ltrim($path, '/');
    }
}

if (! function_exists('media_dimensions')) {
    /** أبعاد الصورة الأصلية (لعرض width/height وتقليل انزياح التصميم) */
    function media_dimensions(?string $path): array
    {
        static $cache = [];

        $path = trim((string) $path);
        if ($path === '' || preg_match('#^(https?:)?//#i', $path)) return [];

        $key = ltrim($path, '/');
        if (array_key_exists($key, $cache)) return $cache[$key];

        $file = public_path($key);
        $size = is_file($file) ? @getimagesize($file) : false;

        return $cache[$key] = $size ? ['width' => (int) $size[0], 'height' => (int) $size[1]] : [];
    }
}

if (! function_exists('img_dims')) {
    /** خصائص width و height الجاهزة للطبع داخل وسم img */
    function img_dims(?string $path): string
    {
        $dims = media_dimensions($path);
        return $dims ? ' width="' . $dims['width'] . '" height="' . $dims['height'] . '"' : '';
    }
}

if (! function_exists('srcset_list')) {
    /** قائمة srcset الخام (بدون خاصية) لاستخدامها في preload */
    function srcset_list(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '' || preg_match('#^(https?:)?//#i', $path)) return '';

        $parts = [];
        foreach (\App\Support\ImageVariants::forPath($path) as $width => $url) {
            $parts[] = $url . ' ' . $width . 'w';
        }

        return implode(', ', $parts);
    }
}

if (! function_exists('img_srcset')) {
    /**
     * خصائص srcset الجاهزة للطبع إن كانت هناك نسخ مصغّرة مولّدة بجانب الصورة الأصلية
     * (تُنشأ تلقائيًا عند رفع الصور من لوحة التحكم أو عبر أمر assets:variants).
     */
    function img_srcset(?string $path): string
    {
        $list = srcset_list($path);

        return $list ? ' srcset="' . e($list) . '"' : '';
    }
}

if (! function_exists('cms_installed')) {
    function cms_installed(): bool
    {
        return is_file(storage_path('app/installed.lock'));
    }
}

if (! function_exists('fa')) {
    /** يضمن أن اسم الأيقونة مكتوب بصيغة Font Awesome صحيحة */
    function fa(?string $icon, string $fallback = 'fa-star'): string
    {
        $icon = trim((string) $icon) ?: $fallback;
        if (str_contains($icon, 'fa-brands') || str_contains($icon, 'fa-solid') || str_contains($icon, 'fa-regular')) return $icon;
        return 'fa-solid ' . (str_starts_with($icon, 'fa-') ? $icon : 'fa-' . $icon);
    }
}

if (! function_exists('ar_greeting')) {
    function ar_greeting(): string
    {
        $h = (int) now()->format('G');
        return $h < 12 ? 'صباح الخير' : 'مساء الخير';
    }
}
