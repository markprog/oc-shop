<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->boolean('approval')->default(false);
            $table->boolean('company_id_display')->default(false);
            $table->boolean('company_id_required')->default(false);
            $table->boolean('company_vat_display')->default(false);
            $table->boolean('company_vat_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('customer_group_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_group_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 32);
            $table->text('description')->nullable();
            $table->primary(['customer_group_id', 'language_id']);
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_group_descriptions');
        Schema::dropIfExists('customer_groups');
    }
};
