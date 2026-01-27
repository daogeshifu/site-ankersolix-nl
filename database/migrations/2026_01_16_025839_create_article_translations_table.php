<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->longText('content')->nullable()->comment('文章内容（翻译）');
            $table->string('summary')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->unique(['article_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_translations');
    }
};
