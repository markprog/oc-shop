<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutRoute extends Model
{
    protected $fillable = ['layout_id', 'route', 'store_id'];

    public function layout(): BelongsTo { return $this->belongsTo(Layout::class); }
}
