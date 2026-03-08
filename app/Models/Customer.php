<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guard = 'web';

    protected $fillable = [
        'store_id', 'customer_group_id', 'firstname', 'lastname', 'email',
        'telephone', 'password', 'newsletter', 'custom_field', 'address_id',
        'ip', 'status', 'safe', 'token',
    ];

    protected $hidden = ['password', 'remember_token', 'token'];

    protected $casts = [
        'custom_field' => 'array',
        'newsletter'   => 'boolean',
        'status'       => 'boolean',
        'safe'         => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function rewardPoints(): HasMany
    {
        return $this->hasMany(CustomerReward::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function affiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function rewardBalance(): int
    {
        return (int) $this->rewardPoints()->sum('points');
    }

    public function creditBalance(): float
    {
        return (float) $this->transactions()->sum('amount');
    }
}
