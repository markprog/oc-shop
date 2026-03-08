<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['information_id', 'language_id', 'title', 'description', 'meta_title', 'meta_description', 'meta_keyword'];
}
