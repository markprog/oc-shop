<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponHistory extends Model
{
    protected $fillable = ['coupon_id', 'order_id', 'customer_id', 'amount'];
    protected $casts = ['amount' => 'decimal:4'];

    public function coupon(): BelongsTo { return $this->belongsTo(Coupon::class); }
}
