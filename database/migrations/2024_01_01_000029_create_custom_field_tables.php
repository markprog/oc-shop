<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32); // text|textarea|select|radio|checkbox|date|time|datetime|file
            $table->string('location', 32)->default('account'); // account|address
            $table->boolean('required')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_field_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_field_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 128);
            $table->primary(['custom_field_id', 'language_id']);
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_field_id')->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->cascadeOnDelete();
        });

        Schema::create('custom_field_value_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_field_value_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('custom_field_id');
            $table->string('name', 128);
            $table->primary(['custom_field_value_id', 'language_id']);
            $table->foreign('custom_field_value_id')->references('id')->on('custom_field_values')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('custom_field_customer_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_field_id');
            $table->unsignedBigInteger('customer_group_id');
            $table->boolean('required')->default(false);
            $table->primary(['custom_field_id', 'customer_group_id']);
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_customer_groups');
        Schema::dropIfExists('custom_field_value_descriptions');
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_field_descriptions');
        Schema::dropIfExists('custom_fields');
    }
};
