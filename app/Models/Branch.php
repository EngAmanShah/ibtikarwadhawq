<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use Orderable;

    protected $fillable = ['title', 'icon', 'phone', 'whatsapp', 'address', 'map_embed', 'map_title', 'is_active', 'sort'];

    protected $casts = ['is_active' => 'boolean'];

    /** عرض الرقم بشكل مقروء: +966 53 519 3153 */
    public function getPhoneDisplayAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);
        if (str_starts_with($digits, '966') && strlen($digits) === 12) {
            return '+966 ' . substr($digits, 3, 2) . ' ' . substr($digits, 5, 3) . ' ' . substr($digits, 8);
        }
        return (string) $this->phone;
    }

    public function getPhoneLinkAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->phone);
        return $digits ? '+' . $digits : '';
    }

    public function getWhatsappNumberAttribute(): string
    {
        return preg_replace('/\D+/', '', (string) ($this->whatsapp ?: $this->phone));
    }

    /** يقبل رابط خريطة جوجل أو كود iframe كامل ويعيد رابط التضمين */
    public function getMapSrcAttribute(): ?string
    {
        $v = trim((string) $this->map_embed);
        if ($v === '') return null;
        if (preg_match('/src="([^"]+)"/i', $v, $m)) return html_entity_decode($m[1]);
        if (str_contains($v, '/maps/embed') || str_contains($v, 'output=embed')) return $v;
        if (str_starts_with($v, 'http')) return 'https://www.google.com/maps?q=' . rawurlencode($v) . '&output=embed';
        return 'https://www.google.com/maps?q=' . rawurlencode($v) . '&output=embed';
    }
}
