<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductReturn extends Model
{
    protected $table = 'product_returns';

    protected $fillable = [
        'order_id', 'product_id', 'customer_id', 'firstname', 'lastname',
        'email', 'telephone', 'product', 'model', 'quantity', 'opened',
        'return_reason_id', 'return_status_id', 'comment', 'date_ordered',
    ];

    protected $casts = ['opened' => 'boolean', 'date_ordered' => 'date'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function reason(): BelongsTo { return $this->belongsTo(ReturnReason::class, 'return_reason_id'); }
    public function status(): BelongsTo { return $this->belongsTo(ReturnStatus::class, 'return_status_id'); }
    public function histories(): HasMany { return $this->hasMany(ReturnHistory::class, 'return_id')->latest(); }
}
