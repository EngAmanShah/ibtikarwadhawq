<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'phone', 'service', 'space', 'message', 'status', 'notes', 'ip'];

    public const STATUSES = [
        'new' => ['label' => 'جديد', 'icon' => 'fa-bell', 'tone' => 'gold'],
        'contacted' => ['label' => 'تم التواصل', 'icon' => 'fa-phone-volume', 'tone' => 'blue'],
        'won' => ['label' => 'تم التعاقد', 'icon' => 'fa-handshake', 'tone' => 'green'],
        'closed' => ['label' => 'مغلق', 'icon' => 'fa-box-archive', 'tone' => 'gray'],
    ];

    public function statusInfo(): array
    {
        return self::STATUSES[$this->status] ?? self::STATUSES['new'];
    }

    public function getWhatsappLinkAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', $this->phone);
        if (str_starts_with($digits, '05')) $digits = '966' . substr($digits, 1);
        elseif (str_starts_with($digits, '5') && strlen($digits) === 9) $digits = '966' . $digits;
        $text = 'مرحبًا ' . $this->name . '، نتواصل معك من ' . setting('site_name') . ' بخصوص طلبك';
        return 'https://wa.me/' . $digits . '?text=' . rawurlencode($text);
    }
}
