<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32); // select|radio|checkbox|image|text|textarea|file|date|time|datetime
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('option_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 128);
            $table->primary(['option_id', 'language_id']);
            $table->foreign('option_id')->references('id')->on('options')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('option_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id')->index();
            $table->string('image', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('option_id')->references('id')->on('options')->cascadeOnDelete();
        });

        Schema::create('option_value_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('option_value_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('option_id');
            $table->string('name', 128);
            $table->primary(['option_value_id', 'language_id']);
            $table->foreign('option_value_id')->references('id')->on('option_values')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('option_value_descriptions');
        Schema::dropIfExists('option_values');
        Schema::dropIfExists('option_descriptions');
        Schema::dropIfExists('options');
    }
};
