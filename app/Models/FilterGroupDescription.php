<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterGroupDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['filter_group_id', 'language_id', 'name'];
}
