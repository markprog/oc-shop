<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('author', 64)->default('');
            $table->tinyInteger('rating')->default(0);
            $table->text('text');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->primary(['customer_id', 'product_id']);
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::create('customer_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('description', 255)->default('');
            $table->integer('points')->default(0); // can be negative
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });

        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('description', 255)->default('');
            $table->decimal('amount', 15, 4)->default(0); // can be negative
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 40)->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->text('option')->nullable(); // serialized options JSON
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
        Schema::dropIfExists('customer_transactions');
        Schema::dropIfExists('customer_rewards');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
    }
};
