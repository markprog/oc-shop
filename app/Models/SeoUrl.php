<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoUrl extends Model
{
    public $timestamps = false;

    protected $fillable = ['store_id', 'language_id', 'key', 'value', 'keyword'];

    public static function findByKeyword(string $keyword, int $storeId = 0): ?self
    {
        return static::where('keyword', $keyword)->where('store_id', $storeId)->first();
    }

    public static function findKeyword(string $key, string $value, int $storeId = 0, int $languageId = 1): ?string
    {
        return static::where('key', $key)->where('value', $value)
            ->where('store_id', $storeId)->where('language_id', $languageId)
            ->value('keyword');
    }
}
