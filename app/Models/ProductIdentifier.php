<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductIdentifier extends Model
{
    protected $fillable = ['product_id', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
