<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxClass extends Model
{
    protected $fillable = ['title', 'description'];

    public function rules(): HasMany { return $this->hasMany(TaxRule::class); }
}
