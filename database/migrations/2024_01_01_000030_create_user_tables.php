<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->json('permission')->nullable(); // {"access": [...routes], "modify": [...routes]}
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_group_id')->index();
            $table->string('username', 20)->unique();
            $table->string('password', 255);
            $table->string('salt', 9)->default('');
            $table->string('firstname', 32)->default('');
            $table->string('lastname', 32)->default('');
            $table->string('email', 96)->default('');
            $table->string('image', 255)->nullable();
            $table->string('code', 40)->nullable();
            $table->string('ip', 40)->default('');
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('user_group_id')->references('id')->on('user_groups')->restrictOnDelete();
        });

        Schema::create('api', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64);
            $table->string('key', 255);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('api_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->index();
            $table->string('session_id', 40);
            $table->string('token', 255);
            $table->string('call', 255)->default('');
            $table->string('ip', 40)->default('');
            $table->timestamps();
            $table->foreign('api_id')->references('id')->on('api')->cascadeOnDelete();
        });

        Schema::create('api_ip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->index();
            $table->string('ip', 40)->default('');
            $table->timestamps();
            $table->foreign('api_id')->references('id')->on('api')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_ip');
        Schema::dropIfExists('api_sessions');
        Schema::dropIfExists('api');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_groups');
    }
};
