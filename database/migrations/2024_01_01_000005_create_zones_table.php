<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->index();
            $table->string('name', 128);
            $table->string('code', 32)->default('');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
        });

        Schema::create('zone_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 128);
            $table->primary(['zone_id', 'language_id']);
            $table->foreign('zone_id')->references('id')->on('zones')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_descriptions');
        Schema::dropIfExists('zones');
    }
};
