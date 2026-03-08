<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerImage extends Model
{
    protected $fillable = ['banner_id', 'language_id', 'title', 'link', 'image', 'sort_order'];

    public function banner(): BelongsTo { return $this->belongsTo(Banner::class); }
    public function language(): BelongsTo { return $this->belongsTo(Language::class); }
}
