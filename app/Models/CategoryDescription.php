<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'category_id', 'language_id', 'name', 'description',
        'meta_title', 'meta_description', 'meta_keyword',
    ];
}
