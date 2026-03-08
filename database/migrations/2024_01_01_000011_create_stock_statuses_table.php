<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->default(0);
            $table->string('name', 32);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_statuses');
    }
};
