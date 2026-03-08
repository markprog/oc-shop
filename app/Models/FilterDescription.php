<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['filter_id', 'language_id', 'filter_group_id', 'name'];
}
