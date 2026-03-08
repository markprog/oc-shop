<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends Model
{
    protected $fillable = ['name'];

    public function modules(): HasMany { return $this->hasMany(LayoutModule::class)->orderBy('sort_order'); }
    public function routes(): HasMany { return $this->hasMany(LayoutRoute::class); }
}
