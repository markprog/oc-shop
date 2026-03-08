<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Information extends Model
{
    protected $table = 'information';
    protected $fillable = ['layout_id', 'sort_order', 'bottom', 'status'];
    protected $casts = ['bottom' => 'boolean', 'status' => 'boolean'];

    public function descriptions(): HasMany { return $this->hasMany(InformationDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(InformationDescription::class)
            ->where('language_id', config('shop.language_id', 1));
    }
}
