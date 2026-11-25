<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use Orderable;

    protected $fillable = ['title', 'image', 'is_active', 'sort'];

    protected $casts = ['is_active' => 'boolean'];

}
