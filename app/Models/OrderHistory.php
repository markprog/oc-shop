<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    protected $fillable = ['order_id', 'order_status_id', 'notify', 'comment'];

    protected $casts = ['notify' => 'boolean'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function status(): BelongsTo { return $this->belongsTo(OrderStatus::class, 'order_status_id'); }
}
