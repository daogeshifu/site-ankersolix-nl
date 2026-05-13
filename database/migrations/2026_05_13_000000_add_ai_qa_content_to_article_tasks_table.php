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
        Schema::table('article_tasks', function (Blueprint $table) {
            $table->longText('ai_qa_content')
                ->nullable()
                ->after('brand_info')
                ->comment('AI 问答摘要内容，提交远程接口时写入 task_context.ai_qa_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_tasks', function (Blueprint $table) {
            $table->dropColumn('ai_qa_content');
        });
    }
};
