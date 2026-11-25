<?php

/*
| ينشئ ملف .env من .env.example عند أول تشغيل ويولّد مفتاح تشفير آمن،
| حتى يعمل معالج التثبيت من المتصفح بدون أي أوامر.
*/

$envFile = __DIR__ . '/../.env';
$example = __DIR__ . '/../.env.example';

if (! is_file($envFile) && is_file($example)) {
    $content = file_get_contents($example);
    $key = 'base64:' . base64_encode(random_bytes(32));
    $content = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY=' . $key, $content);
    @file_put_contents($envFile, $content);
}

if (is_file($envFile)) {
    $content = file_get_contents($envFile);
    if (preg_match('/^APP_KEY=\s*$/m', $content)) {
        $key = 'base64:' . base64_encode(random_bytes(32));
        $content = preg_replace('/^APP_KEY=\s*$/m', 'APP_KEY=' . $key, $content);
        @file_put_contents($envFile, $content);
    }
}
