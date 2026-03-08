<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->default(0)->index();
            $table->unsignedBigInteger('customer_group_id')->default(0)->index();
            $table->string('firstname', 32)->default('');
            $table->string('lastname', 32)->default('');
            $table->string('email', 96)->unique();
            $table->string('telephone', 32)->default('');
            $table->string('password', 255);
            $table->boolean('newsletter')->default(false);
            $table->json('custom_field')->nullable();
            $table->unsignedBigInteger('address_id')->nullable(); // default address (set after)
            $table->string('ip', 40)->default('');
            $table->boolean('status')->default(true);
            $table->boolean('safe')->default(false);
            $table->string('token', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->restrictOnDelete();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();
            $table->string('firstname', 32)->default('');
            $table->string('lastname', 32)->default('');
            $table->string('company', 60)->default('');
            $table->string('address_1', 128)->default('');
            $table->string('address_2', 128)->default('');
            $table->string('city', 128)->default('');
            $table->string('postcode', 10)->default('');
            $table->unsignedBigInteger('country_id')->default(0);
            $table->unsignedBigInteger('zone_id')->default(0);
            $table->json('custom_field')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('customers');
    }
};
