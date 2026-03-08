<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weight_classes', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 8)->default(0);
            $table->timestamps();
        });

        Schema::create('weight_class_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_class_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', 32);
            $table->string('unit', 4);
            $table->primary(['weight_class_id', 'language_id']);
            $table->foreign('weight_class_id')->references('id')->on('weight_classes')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('length_classes', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 8)->default(0);
            $table->timestamps();
        });

        Schema::create('length_class_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('length_class_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', 32);
            $table->string('unit', 4);
            $table->primary(['length_class_id', 'language_id']);
            $table->foreign('length_class_id')->references('id')->on('length_classes')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('length_class_descriptions');
        Schema::dropIfExists('length_classes');
        Schema::dropIfExists('weight_class_descriptions');
        Schema::dropIfExists('weight_classes');
    }
};
