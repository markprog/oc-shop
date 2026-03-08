<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    protected $fillable = ['store_id', 'extension', 'code', 'key', 'value', 'serialized'];

    protected $casts = ['serialized' => 'boolean'];

    public function getValue(): mixed
    {
        return $this->serialized ? unserialize($this->value) : $this->value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->getValue() : $default;
    }

    public static function set(string $key, mixed $value, string $code = 'config', int $storeId = 0): void
    {
        $serialized = !is_string($value);
        static::updateOrCreate(
            ['store_id' => $storeId, 'code' => $code, 'key' => $key],
            ['value' => $serialized ? serialize($value) : $value, 'serialized' => $serialized]
        );
    }
}
