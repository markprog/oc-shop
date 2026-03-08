<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerTransaction extends Model
{
    protected $fillable = ['customer_id', 'order_id', 'description', 'amount'];
    protected $casts = ['amount' => 'decimal:4'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
