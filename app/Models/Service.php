<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use Orderable;

    protected $fillable = [
        'slug', 'title', 'subtitle', 'icon', 'excerpt', 'card_image', 'hero_image', 'hero_title', 'hero_text',
        'cta_text', 'meta_title', 'meta_description', 'in_menu', 'is_active', 'sort',
    ];

    protected $casts = ['is_active' => 'boolean', 'in_menu' => 'boolean'];

    public function items(): HasMany
    {
        return $this->hasMany(ServiceItem::class)->orderBy('sort')->orderBy('id');
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function url(): string
    {
        return route('site.service', $this->slug);
    }
}
