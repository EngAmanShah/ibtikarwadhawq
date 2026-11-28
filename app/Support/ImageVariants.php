<?php

namespace App\Support;

/**
 * نسخ مصغّرة من الصور (WebP) تُستخدم في srcset حتى يحمّل الجوال حجمًا مناسبًا
 * بدلًا من تحميل الصورة الكاملة ثم تصغيرها. النسخ تُنشأ عند الرفع أو عبر:
 * php artisan assets:variants
 */
class ImageVariants
{
    public const WIDTHS = [480, 768, 1200];

    protected static array $memo = [];

    /** [العرض => الرابط] للنسخ الموجودة فعليًا بجانب الصورة الأصلية */
    public static function forPath(?string $path): array
    {
        $path = trim((string) $path);
        if ($path === '' || preg_match('#^(https?:)?//#i', $path)) return [];

        $key = ltrim($path, '/');
        if (array_key_exists($key, self::$memo)) return self::$memo[$key];

        $found = [];
        $file = public_path($key);
        if (! is_file($file)) return self::$memo[$key] = [];

        $dims = @getimagesize($file);
        $originalWidth = $dims ? (int) $dims[0] : 0;

        foreach (self::WIDTHS as $width) {
            if ($originalWidth && $originalWidth < $width) continue;
            $variant = self::variantPath($key, $width);
            if (is_file(public_path($variant))) $found[$width] = media($variant);
        }

        return self::$memo[$key] = $found;
    }

    /** يولّد النسخ المصغّرة للصورة، ويعيد [العرض => المسار النسبي] لما تم إنشاؤه */
    public static function generate(?string $path, array $widths = self::WIDTHS): array
    {
        $path = trim((string) $path);
        if ($path === '' || preg_match('#^(https?:)?//#i', $path)) return [];

        $key = ltrim($path, '/');
        $source = public_path($key);
        if (! is_file($source)) return [];

        $info = @getimagesize($source);
        if (! $info) return [];

        [$originalWidth, $originalHeight] = $info;
        $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        $reader = match ($ext) {
            'webp' => 'imagecreatefromwebp',
            'jpg', 'jpeg' => 'imagecreatefromjpeg',
            'png' => 'imagecreatefrompng',
            default => null,
        };
        if (! $reader || ! function_exists($reader) || ! function_exists('imagewebp')) return [];

        $made = [];
        foreach ($widths as $width) {
            $width = (int) $width;
            if ($width < 16 || $originalWidth <= $width) continue;

            $variant = self::variantPath($key, $width);
            $dest = public_path($variant);
            if (is_file($dest) && filemtime($dest) >= filemtime($source)) {
                $made[$width] = $variant;
                continue;
            }

            $image = @$reader($source);
            if (! $image) continue;

            $height = max(1, (int) round($originalHeight * $width / $originalWidth));
            $canvas = imagecreatetruecolor($width, $height);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

            $ok = @imagewebp($canvas, $dest, 78);
            imagedestroy($canvas);
            imagedestroy($image);

            if (! $ok) {
                @unlink($dest);
                continue;
            }

            $made[$width] = $variant;
        }

        self::$memo[$key] = [];

        return $made;
    }

    public static function variantPath(string $path, int $width): string
    {
        $dir = dirname($path);
        $name = pathinfo($path, PATHINFO_FILENAME);

        return ($dir === '.' ? '' : $dir . '/') . $name . '-' . $width . '.webp';
    }
}
