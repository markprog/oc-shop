<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('subscription_plan_id')->index();
            $table->boolean('trial_status')->default(false);
            $table->date('trial_expire')->nullable();
            $table->string('status', 32)->default('active'); // active|inactive|cancelled|suspended|expired
            $table->json('payment_method')->nullable();
            $table->date('date_next')->nullable()->index();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->restrictOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers')->restrictOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
            $table->foreign('subscription_plan_id')->references('id')->on('subscription_plans')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
