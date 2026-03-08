<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    protected $fillable = [
        'customer_id', 'company', 'website', 'tracking', 'commission',
        'tax', 'payment', 'cheque', 'paypal',
        'bank_name', 'bank_branch_number', 'bank_swift_code', 'bank_account_name', 'bank_account_number',
        'ip', 'status', 'approved',
    ];

    protected $casts = ['status' => 'boolean', 'approved' => 'boolean', 'commission' => 'decimal:2'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }

    public function transactions(): HasMany { return $this->hasMany(AffiliateTransaction::class); }

    public function balance(): float
    {
        return (float) $this->transactions()->sum('amount');
    }
}
