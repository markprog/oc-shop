<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attribute extends Model
{
    protected $fillable = ['attribute_group_id', 'sort_order'];

    public function group(): BelongsTo { return $this->belongsTo(AttributeGroup::class, 'attribute_group_id'); }

    public function descriptions(): HasMany { return $this->hasMany(AttributeDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(AttributeDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
