<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->timestamps();
        });

        Schema::create('layout_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layout_id')->index();
            $table->string('extension', 32)->default('');
            $table->string('position', 32); // column_left|column_right|content_top|content_bottom
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->foreign('layout_id')->references('id')->on('layouts')->cascadeOnDelete();
        });

        Schema::create('layout_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layout_id')->index();
            $table->string('route', 64);  // glob pattern e.g. storefront.catalog.*
            $table->unsignedBigInteger('store_id')->default(0);
            $table->timestamps();
            $table->foreign('layout_id')->references('id')->on('layouts')->cascadeOnDelete();
        });

        Schema::create('seo_urls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->default(0);
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('key', 64);
            $table->string('value', 255);
            $table->string('keyword', 255)->unique();
            $table->timestamp('date_modified')->useCurrent()->useCurrentOnUpdate();
            $table->index(['store_id', 'language_id', 'key', 'value']);
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('extension', 32);
            $table->string('code', 32);
            $table->json('setting')->nullable();
            $table->timestamps();
        });

        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32); // payment|shipping|total|module|analytics|feed|fraud
            $table->string('code', 32);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extensions');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('seo_urls');
        Schema::dropIfExists('layout_routes');
        Schema::dropIfExists('layout_modules');
        Schema::dropIfExists('layouts');
    }
};
