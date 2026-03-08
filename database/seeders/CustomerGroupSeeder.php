<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groupId = DB::table('customer_groups')->insertGetId([
            'approval'             => false,
            'company_id_display'   => false,
            'company_id_required'  => false,
            'company_vat_display'  => false,
            'company_vat_required' => false,
            'sort_order'           => 1,
        ]);

        DB::table('customer_group_descriptions')->insertOrIgnore([[
            'customer_group_id' => $groupId,
            'language_id'       => 1,
            'name'              => 'Default',
            'description'       => 'Default customer group.',
        ]]);
    }
}
