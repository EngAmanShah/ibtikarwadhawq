<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('assets:variants {--width=*}', function () {
    $widths = array_values(array_filter(array_map('intval', (array) $this->option('width'))))
        ?: \App\Support\ImageVariants::WIDTHS;

    $root = public_path('assets/images');
    if (! is_dir($root)) {
        $this->error('مجلد الصور غير موجود: ' . $root);
        return;
    }

    $made = 0;
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        if (! $file->isFile()) continue;
        if (! in_array(strtolower($file->getExtension()), ['webp', 'jpg', 'jpeg', 'png'], true)) continue;

        $relative = str_replace('\\', '/', substr($file->getPathname(), strlen(public_path()) + 1));
        $made += count(\App\Support\ImageVariants::generate($relative, $widths));
    }

    $this->info('تم إنشاء ' . $made . ' نسخة مصغّرة.');
})->purpose('إنشاء نسخ مصغّرة من صور الموقع لاستخدامها في srcset');
