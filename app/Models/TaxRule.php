<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRule extends Model
{
    protected $fillable = ['tax_class_id', 'tax_rate_id', 'based', 'priority'];

    public function taxClass(): BelongsTo { return $this->belongsTo(TaxClass::class); }
    public function taxRate(): BelongsTo { return $this->belongsTo(TaxRate::class); }
}
