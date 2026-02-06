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
            $table->string('title')->nullable()->comment('文章标题');
            $table->longText('content')->nullable()->comment('文章内容');
            $table->string('link', 640)->nullable()->comment('文章链接');
            $table->text('summary')->nullable()->comment('文章摘要');
            $table->string('cover', 640)->nullable()->comment('封面图片');
            $table->string('seo_title', 640)->nullable()->comment('SEO 标题');
            $table->text('seo_description')->nullable()->comment('SEO 描述');
            $table->text('seo_keywords')->nullable()->comment('SEO 关键词');
            $table->unsignedBigInteger('view_count')->default(0)->comment('浏览量（宽松规则）');
            $table->unsignedBigInteger('read_count')->default(0)->comment('有效阅读量（一般严格规则）');
            $table->timestamp('last_viewed_at')->nullable()->comment('最近一次浏览时间');
            $table->timestamp('last_read_at')->nullable()->comment('最近一次有效阅读时间');
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
