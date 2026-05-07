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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'ai_cover')) {
                $table->string('ai_cover', 640)->nullable()->after('cover')->comment('AI 生成封面图片');
            }

            if (!Schema::hasColumn('articles', 'images_processed')) {
                $table->boolean('images_processed')->default(false)->after('ai_cover')->comment('文章图片是否已处理');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'images_processed')) {
                $table->dropColumn('images_processed');
            }

            if (Schema::hasColumn('articles', 'ai_cover')) {
                $table->dropColumn('ai_cover');
            }
        });
    }
};
