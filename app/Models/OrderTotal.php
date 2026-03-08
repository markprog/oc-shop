<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTotal extends Model
{
    protected $fillable = ['order_id', 'extension', 'code', 'title', 'value', 'sort_order'];

    protected $casts = ['value' => 'decimal:4'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
