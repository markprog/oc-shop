<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubscriptionPlan extends Model
{
    protected $fillable = ['sort_order', 'status'];

    protected $casts = ['status' => 'boolean'];

    public function descriptions(): HasMany { return $this->hasMany(SubscriptionPlanDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(SubscriptionPlanDescription::class)->where('language_id', config('shop.language_id', 1));
    }

    public function subscriptions(): HasMany { return $this->hasMany(Subscription::class); }
}
