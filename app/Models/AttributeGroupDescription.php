<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeGroupDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['attribute_group_id', 'language_id', 'name'];
}
