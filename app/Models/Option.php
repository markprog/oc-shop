<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Option extends Model
{
    protected $fillable = ['type', 'sort_order'];

    public function descriptions(): HasMany { return $this->hasMany(OptionDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(OptionDescription::class)->where('language_id', config('shop.language_id', 1));
    }

    public function values(): HasMany { return $this->hasMany(OptionValue::class)->orderBy('sort_order'); }
}
