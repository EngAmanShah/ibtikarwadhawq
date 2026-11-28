<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * رفع الملفات مباشرة إلى public/uploads (يعمل على أي استضافة بدون storage:link)
 * مع ضغط الصور وتحويلها إلى WebP تلقائيًا لتسريع الموقع.
 */
class Uploader
{
    public const IMAGE_RULE = 'image|mimes:jpg,jpeg,png,webp,gif,avif|max:10240';
    public const FILE_RULE = 'file|mimes:pdf|max:30720';

    public static function store(UploadedFile $file, string $folder, int $maxWidth = 1920): string
    {
        $dir = public_path('uploads/' . $folder);
        if (! is_dir($dir)) @mkdir($dir, 0755, true);

        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'file';
        $base = Str::limit($base, 40, '') . '-' . Str::lower(Str::random(6));
        // الامتداد يُستنتج من محتوى الملف الفعلي وليس من اسمه (حماية من رفع ملفات تنفيذية)
        $ext = strtolower((string) $file->extension());
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'pdf'];
        if (! in_array($ext, $allowed, true)) {
            throw new \RuntimeException('نوع الملف غير مسموح');
        }

        if (in_array($ext, ['jpg', 'jpeg', 'png']) && static::canConvert()) {
            $out = static::toWebp($file->getRealPath(), $ext, "$dir/$base.webp", $maxWidth);
            if ($out) {
                \App\Support\ImageVariants::generate("uploads/$folder/$base.webp");
                return "uploads/$folder/$base.webp";
            }
        }

        $file->move($dir, "$base.$ext");
        \App\Support\ImageVariants::generate("uploads/$folder/$base.$ext");
        return "uploads/$folder/$base.$ext";
    }

    public static function delete(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/')) {
            $full = public_path($path);
            if (is_file($full)) @unlink($full);
        }
    }

    protected static function canConvert(): bool
    {
        return function_exists('imagewebp') && function_exists('imagecreatefromjpeg');
    }

    protected static function toWebp(string $src, string $ext, string $dest, int $maxWidth): bool
    {
        try {
            $img = $ext === 'png' ? @imagecreatefrompng($src) : @imagecreatefromjpeg($src);
            if (! $img) return false;

            if ($ext === 'png') {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }

            $w = imagesx($img);
            $h = imagesy($img);
            if ($w > $maxWidth) {
                $nh = (int) round($h * $maxWidth / $w);
                $resized = imagecreatetruecolor($maxWidth, $nh);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $maxWidth, $nh, $w, $h);
                imagedestroy($img);
                $img = $resized;
            }

            $ok = imagewebp($img, $dest, 82);
            imagedestroy($img);
            return $ok && is_file($dest);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
