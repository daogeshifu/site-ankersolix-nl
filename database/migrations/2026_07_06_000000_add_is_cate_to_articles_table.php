<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_cate')
                ->default(false)
                ->after('is_front_visible')
                ->comment('是否已完成 AI 分类和标签')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['is_cate']);
            $table->dropColumn('is_cate');
        });
    }
};
