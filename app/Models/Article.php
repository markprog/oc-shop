<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    protected $fillable = ['article_category_id', 'sort_order', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(ArticleCategory::class, 'article_category_id'); }

    public function descriptions(): HasMany { return $this->hasMany(ArticleDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(ArticleDescription::class)->where('language_id', config('shop.language_id', 1));
    }

    public function comments(): HasMany { return $this->hasMany(CmsComment::class); }
}
