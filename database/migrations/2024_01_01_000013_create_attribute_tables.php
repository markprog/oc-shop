<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('attribute_group_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_group_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 64);
            $table->primary(['attribute_group_id', 'language_id']);
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_group_id')->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->cascadeOnDelete();
        });

        Schema::create('attribute_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 64);
            $table->primary(['attribute_id', 'language_id']);
            $table->foreign('attribute_id')->references('id')->on('attributes')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_descriptions');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('attribute_group_descriptions');
        Schema::dropIfExists('attribute_groups');
    }
};
