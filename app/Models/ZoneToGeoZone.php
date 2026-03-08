<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneToGeoZone extends Model
{
    protected $fillable = ['geo_zone_id', 'country_id', 'zone_id'];

    public function geoZone(): BelongsTo { return $this->belongsTo(GeoZone::class); }
    public function country(): BelongsTo { return $this->belongsTo(Country::class); }
    public function zone(): BelongsTo { return $this->belongsTo(Zone::class); }
}
