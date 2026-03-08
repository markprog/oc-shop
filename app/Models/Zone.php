<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    protected $fillable = ['country_id', 'name', 'code', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
}
