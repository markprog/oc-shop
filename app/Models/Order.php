<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'invoice_no', 'invoice_prefix', 'store_id', 'store_name', 'store_url',
        'customer_id', 'customer_group_id', 'firstname', 'lastname', 'email', 'telephone',
        'custom_field',
        'payment_address_id', 'payment_firstname', 'payment_lastname', 'payment_company',
        'payment_address_1', 'payment_address_2', 'payment_city', 'payment_postcode',
        'payment_country', 'payment_country_id', 'payment_zone', 'payment_zone_id',
        'payment_custom_field', 'payment_method',
        'shipping_address_id', 'shipping_firstname', 'shipping_lastname', 'shipping_company',
        'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_postcode',
        'shipping_country', 'shipping_country_id', 'shipping_zone', 'shipping_zone_id',
        'shipping_custom_field', 'shipping_method',
        'comment', 'total', 'affiliate_id', 'commission', 'marketing_id', 'tracking',
        'language_id', 'language_code', 'currency_id', 'currency_code', 'currency_value',
        'ip', 'forwarded_ip', 'user_agent', 'accept_language', 'order_status_id',
    ];

    protected $casts = [
        'custom_field'          => 'array',
        'payment_custom_field'  => 'array',
        'shipping_custom_field' => 'array',
        'payment_method'        => 'array',
        'shipping_method'       => 'array',
        'total'                 => 'decimal:4',
        'commission'            => 'decimal:4',
        'currency_value'        => 'decimal:8',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function options(): HasManyThrough
    {
        return $this->hasManyThrough(OrderOption::class, OrderProduct::class);
    }

    public function totals(): HasMany
    {
        return $this->hasMany(OrderTotal::class)->orderBy('sort_order');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class)->latest();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function marketing(): BelongsTo
    {
        return $this->belongsTo(Marketing::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}
