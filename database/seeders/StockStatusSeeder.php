<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ["In Stock", "Out of Stock", "Pre-Order", "2-3 Days", "3-5 Days"];

        foreach ($statuses as $name) {
            DB::table("stock_statuses")->insertOrIgnore([
                "language_id" => 1,
                "name"        => $name,
            ]);
        }
    }
}
