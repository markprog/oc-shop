<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategoryDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['article_category_id', 'language_id', 'name', 'description'];
}
