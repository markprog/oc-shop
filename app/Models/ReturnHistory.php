<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnHistory extends Model
{
    protected $fillable = ['return_id', 'return_status_id', 'notify', 'comment'];
    protected $casts = ['notify' => 'boolean'];

    public function productReturn(): BelongsTo { return $this->belongsTo(ProductReturn::class, 'return_id'); }
    public function status(): BelongsTo { return $this->belongsTo(ReturnStatus::class, 'return_status_id'); }
}
