<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValueDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['option_value_id', 'language_id', 'option_id', 'name'];
}
