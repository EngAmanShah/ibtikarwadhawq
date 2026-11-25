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
