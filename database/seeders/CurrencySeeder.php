<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('currencies')->insertOrIgnore([
            ['title' => 'US Dollar',   'code' => 'USD', 'symbol_left' => '$',  'symbol_right' => '',   'decimal_place' => 2, 'value' => 1.00000, 'status' => true],
            ['title' => 'Euro',        'code' => 'EUR', 'symbol_left' => '€',  'symbol_right' => '',   'decimal_place' => 2, 'value' => 0.92000, 'status' => true],
            ['title' => 'Pound Sterling','code'=> 'GBP','symbol_left' => '£',  'symbol_right' => '',   'decimal_place' => 2, 'value' => 0.79000, 'status' => true],
        ]);
    }
}
