<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPath extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['category_id', 'path_id', 'level'];
}
