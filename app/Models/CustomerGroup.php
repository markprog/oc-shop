<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    protected $fillable = ['approval', 'sort_order'];

    protected $casts = ['approval' => 'boolean'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
