<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('description', 255)->default('');
            $table->timestamps();
        });

        Schema::create('zone_to_geo_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('geo_zone_id')->index();
            $table->unsignedBigInteger('country_id')->default(0);
            $table->unsignedBigInteger('zone_id')->default(0); // 0 = all zones in country
            $table->timestamps();
            $table->foreign('geo_zone_id')->references('id')->on('geo_zones')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zone_to_geo_zones');
        Schema::dropIfExists('geo_zones');
    }
};
