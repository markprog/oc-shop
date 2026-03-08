<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['option_id', 'language_id', 'name'];
}
