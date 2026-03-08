<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReward extends Model
{
    protected $fillable = ['product_id', 'customer_group_id', 'points'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function customerGroup(): BelongsTo { return $this->belongsTo(CustomerGroup::class); }
}
