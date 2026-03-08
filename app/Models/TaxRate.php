<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRate extends Model
{
    protected $fillable = ['geo_zone_id', 'name', 'rate', 'type'];
    protected $casts = ['rate' => 'decimal:4'];

    public function geoZone(): BelongsTo { return $this->belongsTo(GeoZone::class); }
}
