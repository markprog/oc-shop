<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('image', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('manufacturer_to_store', function (Blueprint $table) {
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('store_id')->default(0);
            $table->primary(['manufacturer_id', 'store_id']);
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturer_to_store');
        Schema::dropIfExists('manufacturers');
    }
};
