<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['name', 'code', 'locale', 'image', 'directory', 'sort_order', 'status'];
    protected $casts = ['status' => 'boolean'];
}
