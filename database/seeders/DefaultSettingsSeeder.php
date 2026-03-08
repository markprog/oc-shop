<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General config
            ['extension' => '', 'code' => 'config', 'key' => 'config_name',                 'value' => 'My Shop'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_owner',                'value' => 'Shop Owner'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_address',              'value' => ''],
            ['extension' => '', 'code' => 'config', 'key' => 'config_email',                'value' => 'admin@example.com'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_telephone',            'value' => ''],
            ['extension' => '', 'code' => 'config', 'key' => 'config_url',                  'value' => 'http://localhost'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_ssl',                  'value' => 'http://localhost'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_maintenance',          'value' => '0'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_currency',             'value' => 'USD'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_language',             'value' => 'en'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_country_id',           'value' => '223'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_zone_id',              'value' => '0'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_customer_group_id',    'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_customer_price',       'value' => '0'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_tax',                  'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_tax_customer',         'value' => 'shipping'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_checkout_guest',       'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_order_status_id',      'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_complete_status_id',   'value' => '5'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_processing_status_id', 'value' => '2'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_review_status',        'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_review_guest',         'value' => '0'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_product_limit',        'value' => '20'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_login_attempts',       'value' => '5'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_meta_title',           'value' => 'My Shop'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_meta_description',     'value' => ''],
            ['extension' => '', 'code' => 'config', 'key' => 'config_weight_class_id',      'value' => '1'],
            ['extension' => '', 'code' => 'config', 'key' => 'config_length_class_id',      'value' => '1'],
            // Payment extensions
            ['extension' => 'payment', 'code' => 'cod',           'key' => 'payment_cod_status',            'value' => '1'],
            ['extension' => 'payment', 'code' => 'cod',           'key' => 'payment_cod_title',             'value' => 'Cash on Delivery'],
            ['extension' => 'payment', 'code' => 'bank_transfer', 'key' => 'payment_bank_transfer_status',  'value' => '0'],
            ['extension' => 'payment', 'code' => 'bank_transfer', 'key' => 'payment_bank_transfer_title',   'value' => 'Bank Transfer'],
            // Shipping extensions
            ['extension' => 'shipping', 'code' => 'flat', 'key' => 'shipping_flat_status',       'value' => '1'],
            ['extension' => 'shipping', 'code' => 'flat', 'key' => 'shipping_flat_title',        'value' => 'Flat Shipping Rate'],
            ['extension' => 'shipping', 'code' => 'flat', 'key' => 'shipping_flat_cost',         'value' => '5.00'],
            ['extension' => 'shipping', 'code' => 'flat', 'key' => 'shipping_flat_tax_class_id', 'value' => '0'],
            ['extension' => 'shipping', 'code' => 'free', 'key' => 'shipping_free_status',       'value' => '0'],
            ['extension' => 'shipping', 'code' => 'free', 'key' => 'shipping_free_title',        'value' => 'Free Shipping'],
            ['extension' => 'shipping', 'code' => 'free', 'key' => 'shipping_free_minimum',      'value' => '100'],
        ];

        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(
                ['store_id' => 0, 'code' => $s['code'], 'key' => $s['key']],
                ['extension' => $s['extension'], 'value' => $s['value'], 'serialized' => false]
            );
        }
    }
}
