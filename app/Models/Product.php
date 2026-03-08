<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'master_id', 'model', 'location', 'variant', 'override',
        'quantity', 'minimum', 'subtract', 'stock_status_id', 'date_available',
        'manufacturer_id', 'shipping', 'price', 'points',
        'weight', 'weight_class_id', 'length', 'width', 'height', 'length_class_id',
        'status', 'tax_class_id', 'sort_order', 'image', 'viewed',
    ];

    protected $casts = [
        'variant'        => 'array',
        'override'       => 'array',
        'subtract'       => 'boolean',
        'shipping'       => 'boolean',
        'status'         => 'boolean',
        'date_available' => 'date',
        'price'          => 'decimal:4',
        'weight'         => 'decimal:8',
    ];

    public function descriptions(): HasMany
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function description(): HasOne
    {
        return $this->hasOne(ProductDescription::class)
            ->where('language_id', config('shop.language_id', 1));
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function identifier(): HasOne
    {
        return $this->hasOne(ProductIdentifier::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_to_category');
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function productOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(ProductDiscount::class)->orderBy('priority');
    }

    public function specials(): HasMany
    {
        return $this->hasMany(ProductSpecial::class)->orderBy('priority');
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(ProductReward::class);
    }

    public function downloads(): BelongsToMany
    {
        return $this->belongsToMany(Download::class, 'product_to_download');
    }

    public function related(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Product::class, 'master_id');
    }

    public function master(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'master_id');
    }

    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function stockStatus(): BelongsTo
    {
        return $this->belongsTo(StockStatus::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', true);
    }

    public function subscriptionPlans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'product_subscription');
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Setting::class, 'product_to_store', 'product_id', 'store_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForStore($query, int $storeId)
    {
        return $query->whereHas('stores', fn($q) => $q->where('store_id', $storeId));
    }
}
