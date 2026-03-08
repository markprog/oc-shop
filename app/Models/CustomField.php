<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $fillable = ['type', 'location', 'required', 'status', 'sort_order'];
    protected $casts = ['required' => 'boolean', 'status' => 'boolean'];

    public function descriptions(): HasMany { return $this->hasMany(CustomFieldDescription::class); }
    public function values(): HasMany { return $this->hasMany(CustomFieldValue::class); }
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class, 'custom_field_customer_groups')
            ->withPivot('required');
    }
}
