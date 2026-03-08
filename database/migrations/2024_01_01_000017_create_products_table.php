<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_id')->nullable()->index();
            $table->string('model', 64)->default('');
            $table->string('location', 128)->default('');
            $table->json('variant')->nullable();
            $table->json('override')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('minimum')->default(1);
            $table->boolean('subtract')->default(true);
            $table->unsignedBigInteger('stock_status_id')->default(0);
            $table->date('date_available')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable()->index();
            $table->boolean('shipping')->default(true);
            $table->decimal('price', 15, 4)->default(0);
            $table->integer('points')->default(0);
            $table->decimal('weight', 15, 8)->default(0);
            $table->unsignedBigInteger('weight_class_id')->default(0);
            $table->decimal('length', 15, 8)->default(0);
            $table->decimal('width', 15, 8)->default(0);
            $table->decimal('height', 15, 8)->default(0);
            $table->unsignedBigInteger('length_class_id')->default(0);
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('tax_class_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->string('image', 255)->nullable();
            $table->unsignedBigInteger('viewed')->default(0);
            $table->timestamps();

            $table->foreign('master_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->nullOnDelete();
            $table->foreign('stock_status_id')->references('id')->on('stock_statuses')->restrictOnDelete();
        });

        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 255);
            $table->longText('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('tag')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword', 255)->nullable();
            $table->primary(['product_id', 'language_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->string('image', 255);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_identifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique();
            $table->string('sku', 64)->default('')->index();
            $table->string('upc', 12)->default('');
            $table->string('ean', 14)->default('');
            $table->string('jan', 13)->default('');
            $table->string('isbn', 17)->default('');
            $table->string('mpn', 64)->default('');
            $table->string('location', 128)->default('');
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('option_id')->index();
            $table->boolean('required')->default(false);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('option_id')->references('id')->on('options')->cascadeOnDelete();
        });

        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('option_value_id')->index();
            $table->integer('quantity')->default(0);
            $table->boolean('subtract')->default(false);
            $table->decimal('price', 15, 4)->default(0);
            $table->char('price_prefix', 1)->default('+');
            $table->integer('points')->default(0);
            $table->char('points_prefix', 1)->default('+');
            $table->decimal('weight', 15, 8)->default(0);
            $table->char('weight_prefix', 1)->default('+');
            $table->timestamps();
            $table->foreign('product_option_id')->references('id')->on('product_options')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('language_id');
            $table->text('text')->nullable();
            $table->primary(['product_id', 'attribute_id', 'language_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('attribute_id')->references('id')->on('attributes')->cascadeOnDelete();
        });

        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('customer_group_id')->index();
            $table->integer('quantity')->default(1);
            $table->integer('priority')->default(1);
            $table->decimal('price', 15, 4)->default(0);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_specials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('customer_group_id')->index();
            $table->integer('priority')->default(1);
            $table->decimal('price', 15, 4)->default(0);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('product_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('customer_group_id')->index();
            $table->integer('points')->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        // Pivot: product ↔ related product
        Schema::create('product_related', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('related_id');
            $table->primary(['product_id', 'related_id']);
        });

        // Pivot: product ↔ download
        Schema::create('product_to_download', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('download_id');
            $table->primary(['product_id', 'download_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('download_id')->references('id')->on('downloads')->cascadeOnDelete();
        });

        // Pivot: product ↔ store
        Schema::create('product_to_store', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('store_id')->default(0);
            $table->primary(['product_id', 'store_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_to_store');
        Schema::dropIfExists('product_to_download');
        Schema::dropIfExists('product_related');
        Schema::dropIfExists('product_rewards');
        Schema::dropIfExists('product_specials');
        Schema::dropIfExists('product_discounts');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('product_option_values');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('product_identifiers');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('product_descriptions');
        Schema::dropIfExists('products');
    }
};
