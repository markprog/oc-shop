<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderOption extends Model
{
    protected $fillable = [
        'order_id', 'order_product_id', 'product_option_id',
        'product_option_value_id', 'name', 'value', 'type', 'quantity_prefix',
    ];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function orderProduct(): BelongsTo { return $this->belongsTo(OrderProduct::class); }
}
