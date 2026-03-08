<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GdprRequest extends Model
{
    protected $table = 'gdpr';
    protected $fillable = ['customer_id', 'email', 'type', 'status'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
