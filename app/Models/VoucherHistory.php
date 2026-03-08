<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherHistory extends Model
{
    protected $fillable = ['voucher_id', 'order_id', 'amount'];
    protected $casts = ['amount' => 'decimal:4'];

    public function voucher(): BelongsTo { return $this->belongsTo(Voucher::class); }
}
