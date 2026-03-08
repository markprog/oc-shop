<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('name', 32);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_no')->default(0)->index();
            $table->string('invoice_prefix', 26)->default('');
            $table->unsignedBigInteger('store_id')->default(0);
            $table->string('store_name', 64)->default('');
            $table->string('store_url', 255)->default('');
            $table->unsignedBigInteger('customer_id')->default(0)->index();
            $table->unsignedBigInteger('customer_group_id')->default(0);
            $table->string('firstname', 32)->default('');
            $table->string('lastname', 32)->default('');
            $table->string('email', 96)->default('');
            $table->string('telephone', 32)->default('');
            $table->json('custom_field')->nullable();
            // Payment address snapshot
            $table->unsignedBigInteger('payment_address_id')->nullable();
            $table->string('payment_firstname', 32)->default('');
            $table->string('payment_lastname', 32)->default('');
            $table->string('payment_company', 60)->default('');
            $table->string('payment_address_1', 128)->default('');
            $table->string('payment_address_2', 128)->default('');
            $table->string('payment_city', 128)->default('');
            $table->string('payment_postcode', 10)->default('');
            $table->string('payment_country', 128)->default('');
            $table->unsignedBigInteger('payment_country_id')->default(0);
            $table->string('payment_zone', 128)->default('');
            $table->unsignedBigInteger('payment_zone_id')->default(0);
            $table->json('payment_custom_field')->nullable();
            $table->json('payment_method')->nullable();
            // Shipping address snapshot
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->string('shipping_firstname', 32)->default('');
            $table->string('shipping_lastname', 32)->default('');
            $table->string('shipping_company', 60)->default('');
            $table->string('shipping_address_1', 128)->default('');
            $table->string('shipping_address_2', 128)->default('');
            $table->string('shipping_city', 128)->default('');
            $table->string('shipping_postcode', 10)->default('');
            $table->string('shipping_country', 128)->default('');
            $table->unsignedBigInteger('shipping_country_id')->default(0);
            $table->string('shipping_zone', 128)->default('');
            $table->unsignedBigInteger('shipping_zone_id')->default(0);
            $table->json('shipping_custom_field')->nullable();
            $table->json('shipping_method')->nullable();
            $table->text('comment')->nullable();
            $table->decimal('total', 15, 4)->default(0);
            $table->unsignedBigInteger('affiliate_id')->nullable()->index();
            $table->decimal('commission', 15, 4)->nullable();
            $table->unsignedBigInteger('marketing_id')->nullable()->index();
            $table->string('tracking', 64)->nullable();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('language_code', 5)->default('');
            $table->unsignedBigInteger('currency_id')->default(0);
            $table->string('currency_code', 3)->default('');
            $table->decimal('currency_value', 15, 8)->default(1);
            $table->string('ip', 40)->default('');
            $table->string('forwarded_ip', 40)->default('');
            $table->string('user_agent', 255)->default('');
            $table->string('accept_language', 255)->default('');
            $table->unsignedBigInteger('order_status_id')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('master_id')->default(0);
            $table->string('name', 255);       // SNAPSHOT
            $table->string('model', 64);        // SNAPSHOT
            $table->integer('quantity')->default(0);
            $table->decimal('price', 15, 4)->default(0);   // SNAPSHOT
            $table->decimal('total', 15, 4)->default(0);
            $table->decimal('tax', 15, 4)->default(0);
            $table->integer('reward')->default(0);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });

        Schema::create('order_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('order_product_id')->index();
            $table->unsignedBigInteger('product_option_id')->default(0);
            $table->unsignedBigInteger('product_option_value_id')->default(0);
            $table->string('name', 255);    // SNAPSHOT
            $table->text('value');          // SNAPSHOT
            $table->string('type', 32);
            $table->char('quantity_prefix', 1)->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('order_product_id')->references('id')->on('order_products')->cascadeOnDelete();
        });

        Schema::create('order_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('extension', 32)->default('');
            $table->string('code', 32);     // sub_total|shipping|tax|coupon|reward|voucher|total
            $table->string('title', 255);
            $table->decimal('value', 15, 4)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });

        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('order_status_id')->default(0);
            $table->boolean('notify')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_histories');
        Schema::dropIfExists('order_totals');
        Schema::dropIfExists('order_options');
        Schema::dropIfExists('order_products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_statuses');
    }
};
