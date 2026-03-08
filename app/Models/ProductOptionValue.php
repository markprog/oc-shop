<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    protected $fillable = [
        'product_option_id', 'product_id', 'option_value_id',
        'quantity', 'subtract', 'price', 'price_prefix',
        'points', 'points_prefix', 'weight', 'weight_prefix',
    ];

    protected $casts = ['subtract' => 'boolean', 'price' => 'decimal:4'];

    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    public function optionValue(): BelongsTo
    {
        return $this->belongsTo(OptionValue::class);
    }
}
