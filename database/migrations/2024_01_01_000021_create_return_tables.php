<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_reasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('name', 128);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('return_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('name', 32);
            $table->timestamps();
        });

        Schema::create('return_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('name', 32);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->default(0)->index();
            $table->unsignedBigInteger('product_id')->default(0);
            $table->unsignedBigInteger('customer_id')->default(0)->index();
            $table->string('firstname', 32)->default('');
            $table->string('lastname', 32)->default('');
            $table->string('email', 96)->default('');
            $table->string('telephone', 32)->default('');
            $table->string('product', 255);     // SNAPSHOT
            $table->string('model', 64);         // SNAPSHOT
            $table->integer('quantity')->default(0);
            $table->boolean('opened')->default(false);
            $table->unsignedBigInteger('return_reason_id')->default(0);
            $table->unsignedBigInteger('return_status_id')->default(0);
            $table->text('comment')->nullable();
            $table->date('date_ordered')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->restrictOnDelete();
        });

        Schema::create('return_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id')->index();
            $table->unsignedBigInteger('return_status_id')->default(0);
            $table->boolean('notify')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->foreign('return_id')->references('id')->on('product_returns')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_histories');
        Schema::dropIfExists('product_returns');
        Schema::dropIfExists('return_actions');
        Schema::dropIfExists('return_statuses');
        Schema::dropIfExists('return_reasons');
    }
};
