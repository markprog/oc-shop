<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    protected $guard = 'admin';

    protected $fillable = [
        'user_group_id', 'username', 'password', 'firstname', 'lastname',
        'email', 'image', 'ip', 'status',
    ];

    protected $hidden = ['password', 'remember_token', 'salt', 'code'];

    protected $casts = ['status' => 'boolean'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    public function hasPermission(string $type, string $route): bool
    {
        $perms = $this->group?->permission ?? [];
        return in_array($route, $perms[$type] ?? []);
    }
}
