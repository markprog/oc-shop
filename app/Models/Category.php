<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected $fillable = [
        'image', 'parent_id', 'top', 'column', 'sort_order', 'status',
    ];

    protected $casts = [
        'top'    => 'boolean',
        'status' => 'boolean',
    ];

    public function descriptions(): HasMany
    {
        return $this->hasMany(CategoryDescription::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(CategoryDescription::class)
            ->where('language_id', config('shop.language_id', 1));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_to_category');
    }

    public function filters(): BelongsToMany
    {
        return $this->belongsToMany(Filter::class, 'category_filter');
    }

    public function paths(): HasMany
    {
        return $this->hasMany(CategoryPath::class)->orderBy('level');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->where('top', true)->where('status', true)->orderBy('sort_order');
    }
}
