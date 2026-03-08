<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['article_id', 'language_id', 'title', 'description', 'meta_title', 'meta_description'];
}
