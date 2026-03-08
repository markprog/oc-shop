<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('code', 20)->unique();
            $table->char('type', 1)->default('P'); // P=percentage, F=fixed
            $table->decimal('discount', 15, 4)->default(0);
            $table->boolean('logged')->default(false); // requires login
            $table->boolean('shipping')->default(false); // free shipping
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->integer('uses_total')->default(0); // 0 = unlimited
            $table->integer('uses_customer')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('coupon_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->unsignedBigInteger('product_id');
            $table->foreign('coupon_id')->references('id')->on('coupons')->cascadeOnDelete();
        });

        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->unsignedBigInteger('category_id');
            $table->foreign('coupon_id')->references('id')->on('coupons')->cascadeOnDelete();
        });

        Schema::create('coupon_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coupon_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->decimal('amount', 15, 4)->default(0);
            $table->timestamps();
            $table->foreign('coupon_id')->references('id')->on('coupons')->cascadeOnDelete();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('from_name', 64)->default('');
            $table->string('from_email', 96)->default('');
            $table->string('to_name', 64)->default('');
            $table->string('to_email', 96)->default('');
            $table->string('theme', 64)->default('');
            $table->text('message')->nullable();
            $table->decimal('amount', 15, 4)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('voucher_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->decimal('amount', 15, 4)->default(0);
            $table->timestamps();
            $table->foreign('voucher_id')->references('id')->on('vouchers')->cascadeOnDelete();
        });

        Schema::create('marketing', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->text('description')->nullable();
            $table->string('code', 64)->unique();
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('banner_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id')->index();
            $table->unsignedBigInteger('language_id');
            $table->string('title', 64)->default('');
            $table->string('link', 255)->default('');
            $table->string('image', 255)->default('');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('banner_id')->references('id')->on('banners')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_images');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('marketing');
        Schema::dropIfExists('voucher_histories');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('coupon_histories');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupons');
    }
};
