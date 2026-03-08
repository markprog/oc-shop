<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['attribute_id', 'language_id', 'name'];
}
