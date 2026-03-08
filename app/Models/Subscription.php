<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'order_id', 'customer_id', 'product_id', 'subscription_plan_id',
        'trial_status', 'trial_expire', 'status', 'payment_method', 'date_next',
    ];

    protected $casts = [
        'payment_method' => 'array',
        'date_next'      => 'date',
        'trial_expire'   => 'date',
        'trial_status'   => 'boolean',
    ];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function plan(): BelongsTo { return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id'); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
