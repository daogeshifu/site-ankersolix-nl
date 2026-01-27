<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')
                ->default(0)
                ->comment('浏览量（宽松规则）')
                ->after('seo_keywords');

            $table->unsignedBigInteger('read_count')
                ->default(0)
                ->comment('有效阅读量（一般严格规则）')
                ->after('view_count');

            $table->timestamp('last_viewed_at')
                ->nullable()
                ->comment('最近一次浏览时间')
                ->after('read_count');

            $table->timestamp('last_read_at')
                ->nullable()
                ->comment('最近一次有效阅读时间')
                ->after('last_viewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'view_count',
                'read_count',
                'last_viewed_at',
                'last_read_at',
            ]);
        });
    }
};
