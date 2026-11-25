<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Orderable
{
    public static function bootOrderable(): void
    {
        static::creating(function ($model) {
            if (! $model->sort) {
                $query = static::query();
                if (isset($model->group)) $query->where('group', $model->group);
                if (isset($model->service_id)) $query->where('service_id', $model->service_id);
                $model->sort = (int) $query->max('sort') + 1;
            }
        });
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort')->orderBy('id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }
}
