<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('iso_code_2', 2)->default('');
            $table->string('iso_code_3', 3)->default('');
            $table->unsignedBigInteger('address_format_id')->default(0);
            $table->boolean('postcode_required')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('country_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 128);
            $table->primary(['country_id', 'language_id']);
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_descriptions');
        Schema::dropIfExists('countries');
    }
};
