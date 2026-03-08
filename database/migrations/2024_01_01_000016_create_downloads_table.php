<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 160);
            $table->string('mask', 128);
            $table->timestamps();
        });

        Schema::create('download_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('download_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 64);
            $table->primary(['download_id', 'language_id']);
            $table->foreign('download_id')->references('id')->on('downloads')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_descriptions');
        Schema::dropIfExists('downloads');
    }
};
