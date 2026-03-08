<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filter_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('filter_group_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('filter_group_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 64);
            $table->primary(['filter_group_id', 'language_id']);
            $table->foreign('filter_group_id')->references('id')->on('filter_groups')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filter_group_id')->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('filter_group_id')->references('id')->on('filter_groups')->cascadeOnDelete();
        });

        Schema::create('filter_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('filter_id');
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('filter_group_id');
            $table->string('name', 64);
            $table->primary(['filter_id', 'language_id']);
            $table->foreign('filter_id')->references('id')->on('filters')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filter_descriptions');
        Schema::dropIfExists('filters');
        Schema::dropIfExists('filter_group_descriptions');
        Schema::dropIfExists('filter_groups');
    }
};
