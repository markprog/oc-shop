<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CurrencySeeder::class,
            CustomerGroupSeeder::class,
            OrderStatusSeeder::class,
            StockStatusSeeder::class,
            ReturnStatusSeeder::class,
            DefaultSettingsSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
