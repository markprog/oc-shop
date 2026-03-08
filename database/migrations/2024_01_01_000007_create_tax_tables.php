<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 32);
            $table->string('description', 255)->default('');
            $table->timestamps();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('geo_zone_id')->default(0)->index();
            $table->string('name', 32);
            $table->decimal('rate', 15, 4)->default(0);
            $table->char('type', 1)->default('P'); // P=percentage, F=fixed
            $table->timestamps();
        });

        Schema::create('tax_rate_to_customer_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_rate_id');
            $table->unsignedBigInteger('customer_group_id');
            $table->primary(['tax_rate_id', 'customer_group_id']);
        });

        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_class_id')->index();
            $table->unsignedBigInteger('tax_rate_id')->index();
            $table->string('based', 10)->default('shipping'); // shipping|payment|store
            $table->integer('priority')->default(1);
            $table->timestamps();
            $table->foreign('tax_class_id')->references('id')->on('tax_classes')->cascadeOnDelete();
            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rules');
        Schema::dropIfExists('tax_rate_to_customer_groups');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('tax_classes');
    }
};
