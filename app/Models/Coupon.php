<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'discount', 'logged', 'shipping',
        'date_start', 'date_end', 'uses_total', 'uses_customer', 'status',
    ];

    protected $casts = [
        'logged'   => 'boolean',
        'shipping' => 'boolean',
        'status'   => 'boolean',
        'discount' => 'decimal:4',
        'date_start' => 'date',
        'date_end'   => 'date',
    ];

    public function histories(): HasMany { return $this->hasMany(CouponHistory::class); }
    public function products(): HasMany { return $this->hasMany(CouponProduct::class); }
    public function categories(): HasMany { return $this->hasMany(CouponCategory::class); }

    public function isValid(): bool
    {
        if (!$this->status) return false;
        $now = now()->toDateString();
        if ($this->date_start && $this->date_start > $now) return false;
        if ($this->date_end && $this->date_end < $now) return false;
        if ($this->uses_total > 0 && $this->histories()->count() >= $this->uses_total) return false;
        return true;
    }
}
