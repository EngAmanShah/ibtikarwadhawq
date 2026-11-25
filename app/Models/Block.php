<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use Orderable;

    protected $fillable = ['group', 'icon', 'label', 'title', 'text', 'is_active', 'sort'];

    protected $casts = ['is_active' => 'boolean'];

}
