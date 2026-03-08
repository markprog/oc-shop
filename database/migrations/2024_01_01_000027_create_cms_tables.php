<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(0)->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('article_category_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('article_category_id');
            $table->unsignedBigInteger('language_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->primary(['article_category_id', 'language_id']);
            $table->foreign('article_category_id')->references('id')->on('article_categories')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_category_id')->nullable()->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->foreign('article_category_id')->references('id')->on('article_categories')->nullOnDelete();
        });

        Schema::create('article_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', 255);
            $table->longText('description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->primary(['article_id', 'language_id']);
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('topic_descriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('language_id');
            $table->string('title', 255);
            $table->longText('description')->nullable();
            $table->primary(['topic_id', 'language_id']);
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });

        Schema::create('cms_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id')->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('author', 64)->default('');
            $table->text('text');
            $table->boolean('status')->default(false);
            $table->timestamps();
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_comments');
        Schema::dropIfExists('topic_descriptions');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('article_descriptions');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_category_descriptions');
        Schema::dropIfExists('article_categories');
    }
};
