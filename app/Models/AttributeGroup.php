<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttributeGroup extends Model
{
    protected $fillable = ['sort_order'];

    public function attributes(): HasMany { return $this->hasMany(Attribute::class); }

    public function descriptions(): HasMany { return $this->hasMany(AttributeGroupDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(AttributeGroupDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
