<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnReason extends Model
{
    protected $fillable = ['language_id', 'name', 'sort_order'];
}
