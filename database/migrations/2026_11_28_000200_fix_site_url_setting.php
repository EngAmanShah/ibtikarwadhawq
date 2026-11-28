<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * يصحّح "رابط الموقع الأساسي" إذا كان مخزّنًا على نطاق معاينة/تطوير
 * (مثل نطاق الاستضافة المؤقت) لأن ذلك يجعل Canonical و Sitemap يشيران إلى نطاق غير حقيقي
 * فيمتنع ظهور الموقع في نتائج البحث.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) return;

        $current = DB::table('settings')->where('key', 'site_url')->value('value');
        if ($current === null) return;

        $host = strtolower((string) parse_url((string) $current, PHP_URL_HOST));
        if ($this->looksPublic($host)) return;

        DB::table('settings')->updateOrInsert(
            ['key' => 'site_url'],
            ['value' => rtrim((string) config('cms.canonical_url', 'https://ibtikarwadhawq.com'), '/')]
        );

        Cache::forget('cms.settings');
    }

    public function down(): void
    {
        //
    }

    protected function looksPublic(string $host): bool
    {
        if ($host === '' || filter_var($host, FILTER_VALIDATE_IP)) return false;
        if (! preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/', $host)) return false;

        foreach ([
            'localhost', 'local', 'test', 'example', 'invalid',
            'hostingersite.com', 'hostinger.site', 'ngrok.io', 'ngrok-free.app', 'ngrok.app',
            'loca.lt', 'trycloudflare.com', 'vercel.app', 'netlify.app', 'onrender.com',
        ] as $suffix) {
            if ($host === $suffix || str_ends_with($host, '.' . $suffix)) return false;
        }

        return true;
    }
};
