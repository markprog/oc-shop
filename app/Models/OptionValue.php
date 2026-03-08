<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OptionValue extends Model
{
    protected $fillable = ['option_id', 'image', 'sort_order'];

    public function option(): BelongsTo { return $this->belongsTo(Option::class); }

    public function descriptions(): HasMany { return $this->hasMany(OptionValueDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(OptionValueDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
