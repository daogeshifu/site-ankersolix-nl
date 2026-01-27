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
        Schema::create('article_categorys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('SEO 描述');
            $table->text('seo_description')->nullable()->comment('SEO 描述');
            $table->string('seo_title')->nullable()->comment('SEO 标题');
            $table->string('seo_keywords')->nullable()->comment('SEO 关键词');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('父分类 ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_categorys');
    }
};
