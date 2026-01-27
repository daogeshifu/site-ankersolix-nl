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
        Schema::create('article_category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_category_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();

            $table->unique(['article_category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_category_translations');
    }
};
