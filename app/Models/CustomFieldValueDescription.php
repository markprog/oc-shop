<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValueDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['custom_field_value_id', 'language_id', 'custom_field_id', 'name'];
}
