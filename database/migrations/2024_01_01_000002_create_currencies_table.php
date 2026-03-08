<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 32);
            $table->string('code', 3)->unique();
            $table->string('symbol_left', 12)->default('');
            $table->string('symbol_right', 12)->default('');
            $table->tinyInteger('decimal_place')->default(2);
            $table->decimal('value', 15, 8)->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
