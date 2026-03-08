<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FilterGroup extends Model
{
    protected $fillable = ['sort_order'];

    public function filters(): HasMany { return $this->hasMany(Filter::class); }

    public function descriptions(): HasMany { return $this->hasMany(FilterGroupDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(FilterGroupDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
