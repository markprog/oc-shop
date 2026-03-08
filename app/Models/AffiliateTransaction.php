<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateTransaction extends Model
{
    protected $fillable = ['affiliate_id', 'order_id', 'description', 'amount'];
    protected $casts = ['amount' => 'decimal:4'];

    public function affiliate(): BelongsTo { return $this->belongsTo(Affiliate::class); }
}
