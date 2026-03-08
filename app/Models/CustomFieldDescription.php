<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['custom_field_id', 'language_id', 'name'];
}
