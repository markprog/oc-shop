<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecial extends Model
{
    protected $fillable = [
        'product_id', 'customer_group_id', 'priority', 'price', 'date_start', 'date_end',
    ];

    protected $casts = ['price' => 'decimal:4', 'date_start' => 'date', 'date_end' => 'date'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function customerGroup(): BelongsTo { return $this->belongsTo(CustomerGroup::class); }
}
