<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('languages')->insertOrIgnore([
            [
                'name'       => 'English',
                'code'       => 'en',
                'locale'     => 'en_GB.UTF-8,en_GB,en-gb,english',
                'image'      => 'gb.png',
                'directory'  => 'english',
                'sort_order' => 1,
                'status'     => true,
            ],
        ]);
    }
}
