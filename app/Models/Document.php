<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use Orderable;

    protected $fillable = ['title', 'preview_image', 'file', 'expires_at', 'is_active', 'sort'];

    protected $casts = ['expires_at' => 'date', 'is_active' => 'boolean'];

    public function getExpiryStateAttribute(): ?string
    {
        if (! $this->expires_at) return null;
        if ($this->expires_at->isPast()) return 'expired';
        if ($this->expires_at->diffInDays(now()) <= 30) return 'soon';
        return 'ok';
    }
}
