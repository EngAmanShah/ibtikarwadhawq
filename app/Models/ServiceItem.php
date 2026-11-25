<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    use Orderable;

    protected $fillable = ['service_id', 'title', 'icon', 'image', 'points', 'is_active', 'sort'];

    protected $casts = ['points' => 'array', 'is_active' => 'boolean'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
