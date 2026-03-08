<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutModule extends Model
{
    protected $fillable = ['layout_id', 'extension', 'position', 'sort_order'];

    public function layout(): BelongsTo { return $this->belongsTo(Layout::class); }
}
