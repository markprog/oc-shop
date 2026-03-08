<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'subscription_plan_id', 'language_id', 'name',
        'trial_status', 'trial_price', 'trial_cycle', 'trial_frequency', 'trial_duration',
        'price', 'cycle', 'frequency', 'duration',
    ];

    protected $casts = [
        'trial_status' => 'boolean',
        'trial_price'  => 'decimal:4',
        'price'        => 'decimal:4',
    ];
}
