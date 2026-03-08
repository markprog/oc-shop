<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomFieldValue extends Model
{
    protected $fillable = ['custom_field_id', 'sort_order'];

    public function descriptions(): HasMany { return $this->hasMany(CustomFieldValueDescription::class); }
}
