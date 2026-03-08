<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('bottom')->default(false); // show in footer
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('information_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('information_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', 64);
            $table->longText('description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keyword', 255)->nullable();
            $table->primary(['information_id', 'language_id']);
            $table->foreign('information_id')->references('id')->on('information')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('information_to_store', function (Blueprint $table) {
            $table->unsignedBigInteger('information_id');
            $table->unsignedBigInteger('store_id')->default(0);
            $table->primary(['information_id', 'store_id']);
            $table->foreign('information_id')->references('id')->on('information')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('information_to_store');
        Schema::dropIfExists('information_descriptions');
        Schema::dropIfExists('information');
    }
};
