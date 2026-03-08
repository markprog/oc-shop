<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->string('company', 60)->default('');
            $table->string('website', 255)->default('');
            $table->string('tracking', 64)->unique();
            $table->decimal('commission', 4, 2)->default(0);
            $table->string('tax', 64)->default('');
            $table->string('payment', 6)->default('cheque'); // cheque|paypal|bank
            $table->string('cheque', 100)->default('');
            $table->string('paypal', 64)->default('');
            $table->string('bank_name', 64)->default('');
            $table->string('bank_branch_number', 64)->default('');
            $table->string('bank_swift_code', 64)->default('');
            $table->string('bank_account_name', 64)->default('');
            $table->string('bank_account_number', 64)->default('');
            $table->string('ip', 40)->default('');
            $table->boolean('status')->default(false);
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });

        Schema::create('affiliate_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id')->index();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('description', 255)->default('');
            $table->decimal('amount', 15, 4)->default(0);
            $table->timestamps();
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_transactions');
        Schema::dropIfExists('affiliates');
    }
};
