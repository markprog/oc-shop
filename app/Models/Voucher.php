<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'from_name', 'from_email', 'to_name', 'to_email',
        'theme', 'message', 'amount', 'status',
    ];

    protected $casts = ['amount' => 'decimal:4', 'status' => 'boolean'];

    public function histories(): HasMany { return $this->hasMany(VoucherHistory::class); }

    public function remainingAmount(): float
    {
        return $this->amount - (float) $this->histories()->sum('amount');
    }
}
