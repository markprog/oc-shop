<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['title', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'status'];
    protected $casts = ['status' => 'boolean', 'value' => 'decimal:8'];
}
