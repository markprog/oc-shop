<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'product_id', 'language_id', 'name', 'description',
        'short_description', 'tag', 'meta_title', 'meta_description', 'meta_keyword',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
