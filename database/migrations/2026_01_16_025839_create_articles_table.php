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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('文章 ID');
            $table->unsignedBigInteger('user_id')->index('articles_user_id_foreign');
            $table->integer('category_id')->comment('分类 ID');
            $table->string('title')->nullable();
            $table->longText('content')->nullable()->comment('文章内容');
            $table->string('link')->nullable()->comment('文章链接');
            $table->text('summary')->nullable()->comment('文章摘要');
            $table->string('cover')->nullable()->comment('封面图片');
            $table->string('seo_title')->nullable()->comment('SEO 标题');
            $table->text('seo_description')->nullable()->comment('SEO 描述');
            $table->string('seo_keywords')->nullable()->comment('SEO 关键词');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
