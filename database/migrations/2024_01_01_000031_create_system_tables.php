<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->default(0);
            $table->string('extension', 64)->default('');
            $table->string('code', 32);
            $table->string('key', 64);
            $table->text('value')->nullable();
            $table->boolean('serialized')->default(false);
            $table->timestamps();
            $table->index(['store_id', 'code', 'key']);
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('extension', 32);
            $table->string('code', 32);
            $table->string('trigger', 255); // e.g. catalog/controller/product/product/before
            $table->string('action', 255);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('crons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('cycle', 32)->default('day');
            $table->string('action', 255);
            $table->boolean('status')->default(true);
            $table->timestamp('date_scheduled')->nullable();
            $table->timestamp('date_completed')->nullable();
            $table->timestamps();
        });

        Schema::create('gdpr', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('email', 96)->default('');
            $table->string('type', 32)->default('export'); // export|erasure
            $table->string('status', 32)->default('requested'); // requested|approved|denied|completed
            $table->timestamps();
        });

        Schema::create('online', function (Blueprint $table) {
            $table->string('ip', 40)->primary();
            $table->unsignedBigInteger('customer_id')->default(0);
            $table->string('url', 255)->default('');
            $table->string('referer', 255)->default('');
            $table->timestamps();
        });

        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->decimal('value', 15, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('online');
        Schema::dropIfExists('gdpr');
        Schema::dropIfExists('crons');
        Schema::dropIfExists('events');
        Schema::dropIfExists('settings');
    }
};
