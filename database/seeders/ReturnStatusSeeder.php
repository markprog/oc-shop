<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReturnStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ["Awaiting Products", "Complete", "Pending"];

        foreach ($statuses as $name) {
            DB::table("return_statuses")->insertOrIgnore([
                "language_id" => 1,
                "name"        => $name,
            ]);
        }

        DB::table("return_reasons")->insertOrIgnore([
            ["language_id" => 1, "name" => "Dead on Arrival",    "sort_order" => 1],
            ["language_id" => 1, "name" => "Received Wrong Item", "sort_order" => 2],
            ["language_id" => 1, "name" => "Order Error",         "sort_order" => 3],
            ["language_id" => 1, "name" => "Faulty Product",      "sort_order" => 4],
            ["language_id" => 1, "name" => "Other",               "sort_order" => 5],
        ]);
    }
}
