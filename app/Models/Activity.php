<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = ['user_id', 'action', 'subject', 'icon'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $subject, string $icon = 'fa-pen'): void
    {
        try {
            static::create(['user_id' => auth()->id(), 'action' => $action, 'subject' => $subject, 'icon' => $icon]);
        } catch (\Throwable $e) {
            // لا نوقف العملية إذا فشل تسجيل النشاط
        }
    }
}
