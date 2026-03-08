<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            "Pending",
            "Processing",
            "Shipped",
            "Complete",
            "Cancelled",
            "Denied",
            "Canceled Reversal",
            "Failed",
            "Refunded",
            "Reversed",
            "Chargeback",
            "Expired",
            "Processed",
            "Voided",
        ];

        foreach ($statuses as $name) {
            DB::table("order_statuses")->insertOrIgnore([
                "language_id" => 1,
                "name"        => $name,
            ]);
        }
    }
}
