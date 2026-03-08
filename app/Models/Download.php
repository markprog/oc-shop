<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Download extends Model
{
    protected $fillable = ['filename', 'mask'];

    public function descriptions(): HasMany { return $this->hasMany(DownloadDescription::class); }
}
