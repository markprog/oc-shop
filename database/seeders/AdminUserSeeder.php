<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $groupId = DB::table("user_groups")->insertGetId([
            "name"       => "Administrator",
            "permission" => json_encode(["access" => ["*"], "modify" => ["*"]]),
        ]);

        DB::table("users")->insertOrIgnore([
            "user_group_id" => $groupId,
            "username"      => "admin",
            "password"      => Hash::make("admin1234"),
            "firstname"     => "Admin",
            "lastname"      => "User",
            "email"         => "admin@example.com",
            "image"         => null,
            "status"        => true,
            "ip"            => "127.0.0.1",
        ]);
    }
}
