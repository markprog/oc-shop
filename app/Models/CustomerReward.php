<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerReward extends Model
{
    protected $fillable = ['customer_id', 'order_id', 'description', 'points'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
