<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Filter extends Model
{
    protected $fillable = ['filter_group_id', 'sort_order'];

    public function group(): BelongsTo { return $this->belongsTo(FilterGroup::class, 'filter_group_id'); }

    public function descriptions(): HasMany { return $this->hasMany(FilterDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(FilterDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
