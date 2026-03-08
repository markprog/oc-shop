<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marketing extends Model
{
    protected $table = 'marketing';
    protected $fillable = ['name', 'description', 'code', 'clicks'];

    public function orders(): HasMany { return $this->hasMany(Order::class); }
}
