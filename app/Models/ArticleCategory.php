<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleCategory extends Model
{
    protected $fillable = ['parent_id', 'sort_order', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function articles(): HasMany { return $this->hasMany(Article::class); }

    public function descriptions(): HasMany { return $this->hasMany(ArticleCategoryDescription::class); }

    public function description(): HasOne
    {
        return $this->hasOne(ArticleCategoryDescription::class)->where('language_id', config('shop.language_id', 1));
    }
}
