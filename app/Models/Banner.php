<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model
{
    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function images(): HasMany { return $this->hasMany(BannerImage::class)->orderBy('sort_order'); }
}
