<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsComment extends Model
{
    protected $fillable = ['article_id', 'customer_id', 'author', 'text', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function article(): BelongsTo { return $this->belongsTo(Article::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
