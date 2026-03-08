<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('subscription_plan_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('subscription_plan_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 255);
            $table->boolean('trial_status')->default(false);
            $table->decimal('trial_price', 15, 4)->default(0);
            $table->integer('trial_cycle')->default(1);
            $table->string('trial_frequency', 32)->default('day'); // day|week|semi_month|month|year
            $table->integer('trial_duration')->default(0); // 0 = forever
            $table->decimal('price', 15, 4)->default(0);
            $table->integer('cycle')->default(1);
            $table->string('frequency', 32)->default('month');
            $table->integer('duration')->default(0);
            $table->primary(['subscription_plan_id', 'language_id']);
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        // Pivot: product ↔ subscription plan
        Schema::create('product_subscription', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('subscription_plan_id');
            $table->primary(['product_id', 'subscription_plan_id']);
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_subscription');
        Schema::dropIfExists('subscription_plan_descriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
