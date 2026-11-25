<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Orderable;

    protected $fillable = ['name', 'logo', 'url', 'is_active', 'sort'];

    protected $casts = ['is_active' => 'boolean'];

}
