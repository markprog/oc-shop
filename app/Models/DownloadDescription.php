<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadDescription extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['download_id', 'language_id', 'name'];
}
